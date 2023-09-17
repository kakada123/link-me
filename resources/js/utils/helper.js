const socialLogin = {
    google: {
        clientId: "759086904417-u04q9kk7071gekm2aee5tjj43bo54j44.apps.googleusercontent.com",
        redirectUri: "http://localhost:8000/auth/google/callback",
    },
    facebook: {
        clientId: "356814675203304",
        redirectUri: "https://link-me.test/auth/facebook/callback",
    },
};

function baseUrl(url) {
    const currentDomain = window.location.origin;
    return currentDomain + '/' + url;
}

const getIconForType = (type) => {
    switch (type) {
        case "facebook":
            return "pi-facebook";
        case "linkedin":
            return "pi-linkedin";
        case "instagram":
            return "pi-instagram";
        case "pinterest":
            return "pi-pinterest";
        case "twitter":
            return "pi-twitter";
        case "youtube":
            return "pi-youtube";
        case "snapchat":
            return "pi-snapchat";
        case "whatsapp":
            return "pi-whatsapp";
        case "tiktok":
            return "pi-tiktok";
        case "reddit":
            return "pi-reddit";
        case "tumblr":
            return "pi-tumblr";
        case "vimeo":
            return "pi-vimeo";
        case "spotify":
            return "pi-spotify";
        case "soundcloud":
            return "pi-soundcloud";
        case "slack":
            return "pi-slack";
        case "skype":
            return "pi-skype";
        default:
            return "pi-link";
    }
};

export {
    baseUrl,
    socialLogin,
    getIconForType
}
