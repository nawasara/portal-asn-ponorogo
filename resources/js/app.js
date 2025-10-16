import "./bootstrap";
import "preline";
import focus from "@alpinejs/focus";

import AOS from "aos";
import "aos/dist/aos.css";

Alpine.plugin(focus);

document.addEventListener("livewire:init", () => {
    document.addEventListener("alpine:init", () => {
        document.addEventListener("livewire:navigated", () => {
            window.HSStaticMethods.autoInit();
            AOS.init();
        });
    });
});
