<template>
<div class="mb-5">
    <h1 class="text-center text-dark title-section">{{ text }}</h1>
</div>
</template>

<style scoped>
.title-section {
    border-radius: 20px 20px 0% 0%;
    border: 0;
}
.title-section::before {
	content: "";
    padding-left: 0.5rem;
    padding-right: 0.5rem;
    border-right: 1rem solid rgba(246,144,0,1);
    border-left: 0.5rem solid rgba(246,144,0,1);
    margin-right: 10px;
}
.title-section::after {
	content: "";
    padding-left: 0.5rem;
    padding-right: 0.5rem;
    border-left: 1rem solid rgba(246,144,0,1);
    border-right: 0.5rem solid rgba(246,144,0,1);
    margin-left: 10px;
}
</style>

<script>
    export default {
        name: 'sectionTitle',
        props: {
            text: {
                type: String,
                required: true,
            },
        },
    }
</script>