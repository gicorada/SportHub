<?php
$title = $titleHeader ? 'SportHub - '.$titleHeader : 'SportHub';
$active = $activeHeader ?? ''; // es: 'dashboard', 'assemblee'...
?>

<header class="bg-blue-600 text-white p-4 sticky top-0 z-10 shadow-md">
	<div class="max-w-7xl mx-auto flex items-center justify-between">
		<div class="flex items-center gap-3">
			<a href="/dashboard.php" class="flex items-center gap-2">
				<img src="/assets/logo.png" alt="Logo" class="h-20 w-auto">
				<h1 class="text-2xl font-bold"><?= htmlspecialchars($title) ?></h1>
			</a>
		</div>
		
		<nav class="flex items-center gap-6">
			<a href="/dashboard.php" class="hover:text-gray-200">Dashboard</a>
			<a href="/pages/assemblee/visualizza.php" class="<?= $active === 'assemblee' ? 'text-gray-300 underline' : 'hover:text-gray-200' ?>">Assemblee</a>
			<a href="/pages/prenotazioni/prenota.php" class="<?= $active === 'prenota' ? 'text-gray-300 underline' : 'hover:text-gray-200' ?>">Prenotazioni</a>
			<a href="/pages/private/datiPersonali.php" class="<?= $active === 'dati' ? 'text-gray-300 underline' : 'hover:text-gray-200' ?>">Dati Personali</a>
			
			<a href="/utils/user/logout.php" class="text-red-400 hover:text-red-500"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
		</nav>
	</div>
</header>