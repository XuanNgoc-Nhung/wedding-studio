import {
    g as E
} from "./tracking-utils.BsxOwqWB.js";
const l = document.querySelector("[data-download-modal]"),
    u = document.querySelector("[data-download-modal-overlay]"),
    L = document.querySelector("[data-download-modal-close]"),
    a = document.querySelector("[data-download-link-form]"),
    m = document.querySelector("[data-download-link-status]"),
    f = document.querySelector("[data-download-modal-success]"),
    p = document.querySelector("[data-download-modal-title]"),
    h = document.querySelector("[data-download-modal-description]"),
    i = document.querySelector("[data-download-link-submit]"),
    D = document.getElementById("download-modal-email"),
    P = document.body.dataset.mobileDownloadModalEnabled === "true",
    g = a ? .dataset.turnstileSiteKey || "";
let k = null,
    s = null,
    d = "",
    v = "unknown";
const q = () => {
        const e = navigator.userAgentData ? .platform;
        if (typeof e == "string") return e.toLowerCase() === "macos";
        const o = navigator.userAgent || "",
            t = navigator.platform || "",
            n = /iPhone|iPad|iPod/i.test(o),
            c = t === "MacIntel" && navigator.maxTouchPoints > 1;
        return n || c ? !1 : (/Macintosh/i.test(o) || /^Mac/i.test(t)) && !c
    },
    C = new URLSearchParams(window.location.search).has("mobile-preview"),
    w = P && (!q() || C);
w && document.querySelectorAll("[data-download-cta]").forEach(e => {
    const o = e.dataset.modalText,
        t = e.querySelector("[data-download-cta-label]");
    o && t && (t.textContent = o)
});
document.querySelectorAll("[data-download-helper]").forEach(e => {
    e.hidden = !w
});
const T = e => e ? e === "/download" || e.includes("downloads.photoculler.com") : !1,
    x = e => e.closest(".hero") ? "hero" : e.closest(".trial-cta-section") ? "trial_cta" : e.closest(".pricing-card") ? "pricing_card" : "other",
    r = (e, o) => {
        m && (m.textContent = e, m.dataset.state = o)
    },
    y = () => {
        r("", "idle")
    },
    I = async () => {
        !g || window.turnstile || (k || (k = new Promise((e, o) => {
            const t = document.querySelector('script[data-turnstile="download-modal"]');
            if (t) {
                t.addEventListener("load", () => e(), {
                    once: !0
                }), t.addEventListener("error", () => o(new Error("Turnstile script failed to load")), {
                    once: !0
                });
                return
            }
            const n = document.createElement("script");
            n.src = "https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit", n.async = !0, n.defer = !0, n.dataset.turnstile = "download-modal", n.addEventListener("load", () => e(), {
                once: !0
            }), n.addEventListener("error", () => o(new Error("Turnstile script failed to load")), {
                once: !0
            }), document.head.appendChild(n)
        })), await k)
    },
    _ = async () => {
        if (!g || s) return;
        const e = document.getElementById("download-modal-turnstile");
        if (e) try {
            if (await I(), !window.turnstile) {
                r("Verification failed to load. Please refresh and try again.", "error");
                return
            }
            s = window.turnstile.render(e, {
                sitekey: g,
                theme: "dark",
                callback: o => {
                    d = o, m ? .dataset.state === "error" && y()
                },
                "expired-callback": () => {
                    d = ""
                },
                "error-callback": () => {
                    d = "", r("Verification failed. Please try again.", "error")
                }
            })
        } catch {
            r("Verification failed to load. Please refresh and try again.", "error")
        }
    },
    A = () => {
        a ? .reset(), y(), d = "", s && window.turnstile && window.turnstile.reset(s)
    },
    B = e => {
        !w || !l || !u || (v = e, A(), l.hidden = !1, u.hidden = !1, document.body.classList.add("modal-open"), _(), D ? .focus(), window.posthog ? .capture("download_link_modal_opened", {
            source: e,
            page_url: window.location.pathname
        }))
    },
    b = () => {
        !l || !u || (l.hidden = !0, u.hidden = !0, document.body.classList.remove("modal-open"), a && (a.hidden = !1), p && (p.hidden = !1), h && (h.hidden = !1), f && (f.hidden = !0), y())
    };
document.addEventListener("click", e => {
    const t = e.target ? .closest("a[href]");
    !w || !t || !T(t.getAttribute("href")) || (e.preventDefault(), B(x(t)))
});
L ? .addEventListener("click", b);
u ? .addEventListener("click", b);
document.addEventListener("keydown", e => {
    e.key === "Escape" && l && !l.hidden && b()
});
a ? .addEventListener("submit", async e => {
    if (e.preventDefault(), !w || !a || !i) return;
    const o = new FormData(a),
        t = String(o.get("email") || "").trim();
    if (!t) {
        r("Please enter your email address.", "error");
        return
    }
    if (g && !d) {
        r("Please complete verification and try again.", "error");
        return
    }
    const n = i.textContent;
    i.disabled = !0, i.textContent = "Sending...", y();
    try {
        const c = E(),
            S = await fetch("/api/send-download-link", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    email: t,
                    position: v,
                    source: "download_modal",
                    turnstileToken: d,
                    conversionId: c
                })
            }),
            M = await S.json().catch(() => ({}));
        if (!S.ok) {
            r(typeof M.error == "string" ? M.error : "Unable to send the link right now. Please try again.", "error");
            return
        }
        a.hidden = !0, p && (p.hidden = !0), h && (h.hidden = !0), f && (f.hidden = !1), a.reset(), d = "", s && window.turnstile && window.turnstile.reset(s), window.posthog ? .capture("download_link_requested", {
            source: v,
            page_url: window.location.pathname
        }), window.rdt ? .("track", "SignUp", {
            conversionId: c
        })
    } catch {
        r("Unable to send the link right now. Please try again.", "error")
    } finally {
        i.disabled = !1, i.textContent = n
    }
});