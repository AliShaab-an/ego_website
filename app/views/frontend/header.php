<header class="hero relative isolate min-h-[50svh] md:min-h-[120svh]">
    <a href="index.php" class="absolute inset-0 -z-10">
        <img 
            src="<?php echo $header_bg ?? 'assets/images/header2.php';?>" 
            alt="Fashion header"
            class="h-full w-full object-cover"
            loading="eager"
            fetchpriority="high"
            />
        <div class="pointer-events-none absolute inset-x-0 -bottom-1 h-18 bg-gradient-to-t from-white to-transparent"></div>
    </a>

    <!-- Nav -->
    <div class="text-white font-bold py-4">
        <?php include __DIR__ . '/partials/nav.php'; ?>
    </div>
    

  <!-- Hero text -->
  <div class="absolute inset-0 flex flex-col items-center justify-center">
    <h1 class="text-5xl md:text-8xl font-normal font-cor text-white drop-shadow">
      <?php echo $header_title ?? "" ?>
    </h1>
    <h3 class="text-sm md:text-3xl font-outfit font-thin text-white drop-shadow mt-2"><?php echo $header_subtitle ?? ""?></h3>
  </div>
</header>