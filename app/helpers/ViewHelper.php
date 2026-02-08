<?php

    class ViewHelper {

        public static function isActive(string $current, string $expected): string {
            return $current === $expected ? 'active' : '';
        }

        public static function sidebarLink($action,$currentAction,$label,$icon){
            $isActive = $currentAction === $action;
            $classes = $isActive
                ? "bg-brand text-white"
                : "hover:bg-brand hover:text-white text-gray-700";

            echo '<a href="index.php?action=' . $action . '" 
            class="flex items-center gap-2 px-3 py-2 rounded ' . $classes . '">
            <i class="fa-solid ' . $icon . '"></i>
            <span>' . $label . '</span>
            </a>';

        }
    }