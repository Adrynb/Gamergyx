const generateSpaceLayer = (size, selector, totalStars, duration) => {
    const container = document.querySelector(selector);

    for (let i = 0; i < totalStars; i++) {
        const star = document.createElement("div");
        const x = Math.random() * 100;
        const y = Math.random() * 100;

        star.style.position = "absolute";
        star.style.left = `${x}vw`;
        star.style.top = `${y}vh`;
        star.style.width = size;
        star.style.height = size;
        star.style.background = "white";
        star.style.opacity = "0.75";
        star.style.borderRadius = "50%";
        star.style.animation = `starsAnimation ${duration} linear infinite`;
        star.style.animationDelay = `${Math.random() * parseFloat(duration)}s`;

        container.appendChild(star);
    }
};


generateSpaceLayer("1px", ".space1", 400, "25s");
generateSpaceLayer("2px", ".space2", 200, "20s");
generateSpaceLayer("4px", ".space3", 100, "25s");


