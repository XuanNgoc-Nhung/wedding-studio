import {
    g as u,
    s as g
} from "./tracking-utils.BsxOwqWB.js";
const h = (s, r = 50) => {
    let a = 0;
    const i = () => {
        window.posthog ? s() : a < r && (a++, setTimeout(i, 100))
    };
    i()
};
h(() => {
    const s = document.body.dataset.mobileDownloadModalEnabled === "true",
        r = () => {
            const t = navigator.userAgentData ? .platform;
            if (typeof t == "string") return t.toLowerCase() === "macos";
            const e = navigator.userAgent || "",
                o = navigator.platform || "",
                c = /iPhone|iPad|iPod/i.test(e),
                n = o === "MacIntel" && navigator.maxTouchPoints > 1;
            return c || n ? !1 : /Macintosh/i.test(e) || /^Mac/i.test(o)
        },
        a = t => {
            const e = t.getAttribute("href"),
                o = e === "/download" || (e ? e.includes("downloads.photoculler.com") : !1);
            return s && o && !r()
        };
    document.addEventListener("click", t => {
        const o = t.target.closest('a[href*="downloads.photoculler.com"], a[href="/download"], a.download-btn');
        if (o && window.posthog) {
            if (a(o)) return;
            const c = o.closest(".hero") ? "hero" : o.closest(".trial-cta-section") ? "trial_cta" : o.closest(".pricing-card") ? "pricing_card" : "other",
                n = u();
            window.posthog.capture("download_button_clicked", {
                location: c,
                button_text: o.innerText.trim(),
                page_url: window.location.pathname
            }), g("rdt_lead_cid", n), window.rdt ? .("track", "Lead", {
                conversionId: n
            })
        }
    }), document.addEventListener("click", t => {
        const o = t.target.closest('a[href*="shop.photoculler.com"], a[href^="/checkout"], a.nav-buy, a.mobile-menu-cta');
        if (o && window.posthog) {
            const c = o.classList.contains("nav-buy") ? "header" : o.classList.contains("mobile-menu-cta") ? "mobile_menu" : o.closest(".pricing-card") ? "pricing_card" : "other";
            window.posthog.capture("buy_button_clicked", {
                location: c,
                button_text: o.innerText.trim(),
                page_url: window.location.pathname
            })
        }
    });
    const i = [25, 50, 75, 100],
        d = new Set,
        w = () => {
            const t = window.innerHeight,
                e = document.documentElement.scrollHeight,
                c = (window.scrollY + t) / e * 100;
            i.forEach(n => {
                c >= n && !d.has(n) && window.posthog && (d.add(n), window.posthog.capture("scroll_depth_reached", {
                    depth: n,
                    page_url: window.location.pathname
                }))
            })
        };
    let l;
    window.addEventListener("scroll", () => {
        l && window.clearTimeout(l), l = window.setTimeout(w, 100)
    }, {
        passive: !0
    }), w()
});