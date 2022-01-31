<template>
    <div class="container-fluid mb-5 py-5">
        <section class="container">
            <section-title :text="newslettersTitle"></section-title>
            <div class="row row-cols-1 row-cols-xs-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-3">
                <v-card :key="key" v-for="(newsletter, key) in newsletters" :image="newsletter.thumb" :alt="newsletter.alt" :content="newsletter.content" :date="newsletter.createdAt" :button="newsletter.button" :id="newsletter.id" :link="newsletter.link" />
            </div>
        </section>
    </div>
</template>
<style scoped>
.bblack {
    border: 2px solid black;
    box-shadow: 0px 0px 7px 4px #efefee;
    border-radius: 5px;
}
</style>
<script>
    import SectionTitle from '../components/sectionTitle';
    import VCard from '../components/card';
    export default {
        name: 'Home',
        components: {
            VCard,
            SectionTitle,
        },
        props: ['newslettersTitle', 'newsletters'],
        data() {
            return {
            };
        },
    };
</script>