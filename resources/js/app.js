import Alpine from "alpinejs";
// Chart.js (auto registers everything you need)
import Chart from "chart.js/auto";

window.Alpine = Alpine;
window.Chart = Chart;

window.toast = (message, type = "info", options = {}) => {
    window.dispatchEvent(
        new CustomEvent("app-toast", {
            detail: { message, type, ...options },
        })
    );
};

window.toastStack = () => ({
    toasts: [],
    seed: 0,
    toneMap: {
        success: {
            body: "border-emerald-200/70 bg-emerald-50/95 text-emerald-900 dark:border-emerald-500/30 dark:bg-emerald-500/15 dark:text-emerald-100",
            icon: "text-emerald-600 dark:text-emerald-300",
            iconBg: "bg-emerald-100 dark:bg-emerald-500/20",
        },
        error: {
            body: "border-rose-200/70 bg-rose-50/95 text-rose-900 dark:border-rose-500/30 dark:bg-rose-500/15 dark:text-rose-100",
            icon: "text-rose-600 dark:text-rose-300",
            iconBg: "bg-rose-100 dark:bg-rose-500/20",
        },
        warning: {
            body: "border-amber-200/70 bg-amber-50/95 text-amber-900 dark:border-amber-500/30 dark:bg-amber-500/15 dark:text-amber-100",
            icon: "text-amber-600 dark:text-amber-300",
            iconBg: "bg-amber-100 dark:bg-amber-500/20",
        },
        info: {
            body: "border-slate-200/70 bg-white/95 text-slate-900 dark:border-slate-700/70 dark:bg-slate-900/95 dark:text-slate-100",
            icon: "text-slate-600 dark:text-slate-300",
            iconBg: "bg-slate-100 dark:bg-slate-800/60",
        },
    },
    init(initial = []) {
        initial.forEach((toast) => this.add(toast));
        window.addEventListener("app-toast", (event) => this.add(event.detail));
    },
    add({ message = "", type = "info", title = "", timeout = 4500 } = {}) {
        if (!message) return;
        const tone = this.toneMap[type] || this.toneMap.info;
        const id = ++this.seed;
        const label = type.charAt(0).toUpperCase() + type.slice(1);
        this.toasts.push({
            id,
            message,
            title,
            type,
            label,
            tone,
            show: true,
        });

        if (timeout && timeout > 0) {
            setTimeout(() => this.dismiss(id), timeout);
        }
    },
    dismiss(id) {
        const toast = this.toasts.find((item) => item.id === id);
        if (!toast) return;
        toast.show = false;
        setTimeout(() => {
            this.toasts = this.toasts.filter((item) => item.id !== id);
        }, 200);
    },
});

Alpine.start();
