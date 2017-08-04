<template>
    
    <span>
        <a href="#" v-if="isFavorited" @click.prevent="unFavorite(favPost)">
            <i class="material-icons">star</i>
        </a>
        <a href="#" v-else @click.prevent="favorite(favPost)">
            <i class="material-icons">star_border</i>
        </a>
    </span>

    
    
</template>




<script>
    import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import VueAxios from 'vue-axios'

Vue.use(Vuex)
Vue.use(VueAxios, axios)
export default {
    name: 'favorite',
    props: {
        favPost: {
            type: Number,
            default: 1
        },
        favorited: {
        }
    },
    data: function() {
            return {
                isFavorited: '',
            }
        },
    mounted() {
            this.isFavorited = this.isFavorite ? true : false;
        },
    computed: {
            isFavorite() {
                return this.favorited;
            },
        
        },
    methods: {
            favorite(post) {
                axios.post('/favorite/'+this.favPost)
                    .then(response => this.isFavorited = true)
                    .catch(response => console.log(response.data));
            },  

            unFavorite(post) {
                axios.post('/unfavorite/'+this.favPost)
                    .then(response => this.isFavorited = false)
                    .catch(response => console.log(response.data));
            }
        }
    
}



</script>