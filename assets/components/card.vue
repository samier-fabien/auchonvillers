<template>
<div class="col mb-4">
    <article class="col card bg-light text-dark shadow">
        <img class="card-img p-0" :src="image" :alt="alt">

        <div class="card-img-overlay p-4 d-flex flex-column flex-grow hidden-background">
            <div class="mb-auto mt-auto text-center">
                <a class="hidden-button btn btn-lg btn-outline-dark px-5 fs-2 fw-bolder" :href="link">{{ button }}</a>
            </div>
        </div>

        <div class="card-body">
            <h5 class="card-title fs-4">{{ date }}</h5>
            <p class="card-text fs-5">{{ content }}</p>
        </div>
    </article>
</div>
</template>

<style scoped>
.hidden-button {
    border-radius: 30px;
    opacity: 0;
    transform: translateY(50px);
    transition: all 0.4s;
}
.hidden-background {
    background-color: rgba(247, 132, 39, 0);
    --bs-bg-opacity: 0;
}
.card, .card-img-overlay{
    border-radius: 1rem;
}
article.card img {
    border-radius: 1rem 1rem 0rem 0rem;
}
.card-body {
    z-index: 1;
}

/* Au survol */
.card:hover .hidden-button {
    opacity: 1;
    transform: translateY(0px);
}
.card:hover .hidden-background {
    background-color: rgba(246,144,0,0.7);
    --bs-bg-opacity: 0.5;
    transition: all 0.8s;
}

/* Version mobile : au toucher */
.card:active .hidden-button {
    opacity: 1;
    transform: translateY(0px);
}
.card:active .hidden-background {
    background-color: rgba(246,144,0,0.7);
    --bs-bg-opacity: 0.5;
    transition: all 0.8s;
}
</style>

<script>
    export default {
        name: 'vCard',
        props: {
            id: {
                type: Number,
                required: true,
            },
            image: {
                type: String,
                required: true,
            },
            alt: {
                type: String,
                required: true,
            },
            content: {
                type: String,
                required: true,
            },
            date: {
                type: String,
                required: true,
            },
            button: {
                type: String,
                required: true,
            },
            link: {
                type: String,
                required: true,
            }
        },
    }
</script>