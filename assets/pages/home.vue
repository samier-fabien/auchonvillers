<template>
    <div class="container-fluid mb-5 py-5">
        <section class="container">
            <h1 class="text-center mb-4">Dernières actualités</h1>  
            <div class="row row-cols-1 row-cols-xs-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-3">
                <v-card :key="key" v-for="(newsletter, key) in newsletters" :image="newsletter.thumb" :alt="newsletter.alt" :content="newsletter.content | lowercase" :date="newsletter.createdAt | date" :button="newsletter.button" :id="newsletter.id" />
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
    import LegendComponent from '../components/legend';
    import VCard from '../components/card';
    export default {
        name: 'Home',
        components: {
            LegendComponent,
            VCard,
        },
        props: ['machin', 'newsletters'],
        data() {
            return {
                firstname: 'Fabien',
                legend: 'La légende à afficher',
            };
        },
    };
</script>