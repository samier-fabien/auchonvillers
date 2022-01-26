let navbarBreakpoint = 992;
let lastKnownScrollPosition = 0;
let scrollUpdate = false;
let lastKnownInnerWidth = window.innerWidth;
let widthUpdate = false;

function chooseNavbarOpacity(scrollPos, width) {
    if (width >= navbarBreakpoint) {
        if (scrollPos > 50) {
            document.getElementById("mainNavbar").classList.remove("bg-opacity-10");
        } else {
            document.getElementById("mainNavbar").classList.add("bg-opacity-10");
        }
    } else {
        document.getElementById("mainNavbar").classList.remove("bg-opacity-10");
    }
}

window.addEventListener('resize', function(e) {
    lastKnownInnerWidth = window.innerWidth;
    if (!widthUpdate) {
        window.requestAnimationFrame(function() {
            chooseNavbarOpacity(lastKnownScrollPosition, lastKnownInnerWidth);
            widthUpdate = false;
        });

        widthUpdate = true;
    }
});

window.addEventListener('scroll', function(e) {
    lastKnownScrollPosition = window.scrollY;
    if (!scrollUpdate) {
        window.requestAnimationFrame(function() {
            chooseNavbarOpacity(lastKnownScrollPosition, lastKnownInnerWidth);
            scrollUpdate = false;
        });

        scrollUpdate = true;
    }
});

window.onload = chooseNavbarOpacity(lastKnownScrollPosition, lastKnownInnerWidth);
