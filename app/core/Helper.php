<?php

    class Helper{
        public static function redirect($url){
            header("Location: $url");
            exit();
        }

        public static function formatPrice($amount) {
        return "$" . number_format($amount, 2);
        }

        public static function sidebarLink($action,$currentAction,$label,$icon){
            $isActive = $currentAction === $action;
            $classes = $isActive
                ? "bg-[rgba(183,146,103,1)] text-white"
                : "hover:bg-[rgba(183,146,103,1)] hover:text-white text-gray-700";

            echo '<a href="index.php?action=' . $action . '" 
            class="flex items-center gap-2 px-3 py-2 rounded ' . $classes . '">
            <i class="fa-solid ' . $icon . '"></i>
            <span>' . $label . '</span>
            </a>';

        }
    }
