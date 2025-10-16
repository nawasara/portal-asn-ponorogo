import "./bootstrap";
import "preline";
// import Alpine from "alpinejs";
import focus from "@alpinejs/focus";

Alpine.plugin(focus);

document.addEventListener("livewire:init", () => {
    document.addEventListener("alpine:init", () => {
        document.addEventListener("livewire:navigated", () => {
            window.HSStaticMethods.autoInit();
        });
    });
});
