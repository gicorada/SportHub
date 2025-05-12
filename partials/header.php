<?php
$title = $titleHeader ? 'SportHub - '.$titleHeader : 'SportHub';
$active = $activeHeader ?? '';
?>

<header class="bg-blue-600 text-white p-4 sticky top-0 z-10 shadow-md">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="/dashboard.php" class="flex items-center gap-2">
                <img src="/assets/logo.png" alt="Logo" class="h-20 w-auto">
                <h1 class="text-2xl font-bold"><?= htmlspecialchars($title) ?></h1>
            </a>
        </div>
        
        <div class="md:hidden">
            <button id="burger-menu" class="text-white focus:outline-none">
                <i class="fa fa-bars text-2xl"></i>
            </button>
        </div>

        <nav id="nav-menu" class="hidden md:flex items-center gap-6">
            <a href="/dashboard.php" class="hover:text-gray-200">Dashboard</a>
            <a href="/pages/assemblee/visualizza.php" class="<?= $active === 'assemblee' ? 'text-gray-300 underline' : 'hover:text-gray-200' ?>">Assemblee</a>
            <a href="/pages/prenotazioni/prenota.php" class="<?= $active === 'prenota' ? 'text-gray-300 underline' : 'hover:text-gray-200' ?>">Prenotazioni</a>
            <a href="/pages/private/datiPersonali.php" class="<?= $active === 'dati' ? 'text-gray-300 underline' : 'hover:text-gray-200' ?>">Dati Personali</a>
            
            <a href="/utils/user/logout.php" class="text-red-400 hover:text-red-500"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
        </nav>
    </div>

    <!-- Menu mobile -->
    <div id="mobile-menu" class="hidden bg-blue-700 p-4 md:hidden">
        <a href="/dashboard.php" class="block text-white hover:text-gray-200 mb-2">Dashboard</a>
        <a href="/pages/assemblee/visualizza.php" class="block text-white hover:text-gray-200 mb-2 <?= $active === 'assemblee' ? 'text-gray-300 underline' : '' ?>">Assemblee</a>
        <a href="/pages/prenotazioni/prenota.php" class="block text-white hover:text-gray-200 mb-2 <?= $active === 'prenota' ? 'text-gray-300 underline' : '' ?>">Prenotazioni</a>
        <a href="/pages/private/datiPersonali.php" class="block text-white hover:text-gray-200 mb-2 <?= $active === 'dati' ? 'text-gray-300 underline' : '' ?>">Dati Personali</a>
        <a href="/utils/user/logout.php" class="block text-red-400 hover:text-red-500"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
    </div>
</header>


<script>
    document.getElementById('burger-menu').addEventListener('click', function () {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });
</script>