<?php
// DashboardHome.php

session_start();
require 'database.php'; // Assume you have a database connection file
require 'functions.php'; // Assume you have a functions file for common functions

// Fetch user data
$userEmail = $_SESSION['user_email']; // Assuming user email is stored in session
$userData = getUser Data($userEmail); // Function to get user data from the database
$allClubs = getAllClubs(); // Function to get all clubs
$allEvents = getAllEvents(); // Function to get all events

$upcomingEvents = [];
$analyticsData = [
    'eventStats' => [],
    'budgetDistribution' => [],
    'clubStats' => [
        'totalClubs' => 0,
        'activeClubs' => 0,
        'totalBudget' => 0,
        'currentMonth' => [
            'budget' => 0,
            'events' => 0,
            'guestPasses' => 0
        ],
        'averages' => [
            'budgetPerEvent' => 0,
            'eventsPerClub' => 0
        ]
    ]
];

// Fetch and process analytics data
if ($allEvents && $allClubs) {
    // Event statistics by month
    $eventsByMonth = [];
    foreach ($allEvents as $event) {
        $month = date('M', strtotime($event['date']));
        $eventsByMonth[$month] = ($eventsByMonth[$month] ?? 0) + 1;
    }

    // Budget distribution by club
    $budgetByClub = [];
    foreach ($allEvents as $event) {
        if (!empty($event['budget'])) {
            $clubName = strtoupper(explode('@', $event['clubMail'])[0]);
            $budgetByClub[$clubName] = ($budgetByClub[$clubName] ?? 0) + $event['budget'];
        }
    }

    // Calculate current month stats
    $currentMonthStats = [
        'eventCount' => 0,
        'budget' => 0,
        'guestPasses' => 0
    ];
    $currentDate = new DateTime();
    foreach ($allEvents as $event) {
        $eventDate = new DateTime($event['date']);
        if ($eventDate->format('m') == $currentDate->format('m') && $eventDate->format('Y') == $currentDate->format('Y')) {
            $currentMonthStats['eventCount']++;
            $currentMonthStats['budget'] += $event['budget'] ?? 0;
            $currentMonthStats['guestPasses'] += $event['guestPassesCount'] ?? 0;
        }
    }

    // Set analytics data
    $analyticsData['eventStats'] = array_map(function($month, $count) {
        return ['month' => $month, 'events' => $count];
    }, array_keys($eventsByMonth), $eventsByMonth);

    $analyticsData['budgetDistribution'] = array_map(function($name, $value) {
        return ['name' => $name, 'value' => $value];
    }, array_keys($budgetByClub), $budgetByClub);

    $analyticsData['clubStats']['totalClubs'] = count($allClubs);
    $analyticsData['clubStats']['activeClubs'] = count(array_unique(array_column($allEvents, 'clubMail')));
    $analyticsData['clubStats']['totalBudget'] = array_sum(array_column($allEvents, 'budget'));
    $analyticsData['clubStats']['currentMonth'] = $currentMonthStats;
}

// Fetch upcoming events
$upcomingEvents = array_filter($allEvents, function($event) {
    return new DateTime($event['date']) >= new DateTime();
});
usort($upcomingEvents, function($a, $b) {
    return strtotime($a['date']) - strtotime($b['date']);
});
$upcomingEvents = array_slice($upcomingEvents, 0, 5); // Only show next 5 events

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your CSS file -->
</head>
<body>
    <div class="min-h-screen">
        <!-- Header ```php
        <div class="navbar p-0 mt-[-20px] mb-6">
            <div class="flex-1">
                <h1 class="text-[1.62rem] font-bold text-[#303972]">Dashboard</h1>
            </div>
            <div class="flex-none gap-4">
                <div class="dropdown dropdown-end">
                    <div tabIndex="0" role="button" class="btn btn-ghost btn-circle avatar ring-2 ring-[#4c44b3] ring-opacity-30">
                        <div class="w-10 rounded-full">
                            <a href="/dashboard/club-info/<?php echo $userData['_id']; ?>">
                                <img src="<?php echo $userData['photo_url']; ?>" alt="avatar" class="object-cover" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-8">
            <!-- Left Section -->
            <div class="w-[70%] space-y-6">
                <div class="bg-white rounded-xl p-6 mb-6">
                    <div class="flex items-start gap-8">
                        <div class="relative">
                            <img src="<?php echo $userData['photo_url']; ?>" alt="<?php echo $userData['name']; ?>" class="w-24 h-24 rounded-xl object-cover ring-4 ring-[#4c44b3]/10" />
                            <div class="absolute -bottom-2 -right-2 h-8 w-8 bg-[#4c44b3] rounded-lg flex items-center justify-center">
                                <span class="text-white text-xs font-medium"><?php echo $userData['role'] === 'oca' ? 'OCA' : 'CLUB'; ?></span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h1 class="text-xl font-bold text-[#303972]"><?php echo $userData['name']; ?></h1>
                                    <p class="text-gray-500"><?php echo $userData['email']; ?></p>
                                    <?php if (!empty($userData['fullName'])): ?>
                                        <p class="text-[#4c44b3] font-medium mt-1 text-base"><?php echo $userData['fullName']; ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="flex gap-3">
                                    <?php if (!empty($userData['advisors'])): ?>
                                        <div class="text-center px-4 py-2 bg-[#4c44b3]/5 rounded-lg">
                                            <p class="text-2xl font-bold text-[#4c44b3]"><?php echo count($userData['advisors']); ?></p>
                                            <p class="text-sm text-gray-600">Advisors</p>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($userData['panel'])): ?>
                                        <div class="text-center px-4 py-2 bg-[#FB7D5B]/5 rounded-lg">
                                            <p class="text-2xl font-bold text-[#FB7D5B]"><?php echo count($userData['panel']); ?></p>
                                            <p class="text-sm text-gray-600">Panel Members</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-bold text-[#303972] mb-4">Upcoming Events</h2>
                    <div class="space-y-4">
                        <?php if (count($upcomingEvents) > 0): ?>
                            <?php foreach ($upcomingEvents as $event): ?>
                                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="p-3 bg-[#4c44b3]/10 rounded-lg">
                                        <i class="icon-calendar text-[#4c44b3] h-6 w-6"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-[#303972]"><?php echo $event['title']; ?></h3>
                                        <div class="flex items-center gap-4 mt-1">
                                            <span class="text-xs text-gray-500"><?php echo date('F j, Y', strtotime($event['date'])); ?></span>
                                            < span class="text-xs font-medium text-[#4c44b3]"><?php echo strtoupper(explode('@', $event['clubMail'])[0]); ?></span>
                                        </div>
                                    </div>
                                    <?php if (!empty($event['roomNumber'])): ?>
                                        <span class="px-3 py-1 bg-[#4c44b3]/10 text-[#4c44b3] text-sm rounded-lg"><?php echo $event['roomNumber']; ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-8 text-gray-500">No upcoming events</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right Section - Announcements -->
            <div class="w-[30%] h-[85vh] overflow-y-scroll">
                <?php include 'Announcements.php'; // Include your announcements file ?>
            </div>
        </div>
    </div>
</body>
</html>
