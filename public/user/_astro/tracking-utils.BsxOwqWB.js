const n = () => crypto.randomUUID ? crypto.randomUUID() : `${Date.now()}-${Math.random().toString(36).slice(2,10)}`,
    t = (o, e) => {
        document.cookie = `${o}=${encodeURIComponent(e)}; path=/; max-age=60; SameSite=Lax`
    };
export {
    n as g, t as s
};