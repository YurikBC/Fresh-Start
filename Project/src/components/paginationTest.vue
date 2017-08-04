<template>

<div>

<br>

    <div class="pagination">

    <div class="left">
        <div class="pagination_left pag" v-show="this.$route.params.page > 1">
        <router-link :to= "{name: 'page', params: {page: this.$route.params.page-1}}">
                <i class="material-icons">navigate_before</i>
        </router-link>
        
         <div class="buffer" v-if="this.$route.params.page <= 3"></div>
         
         </div>
       <div class="buffer_left"></div>
    </div>
       
    <div class="pagination_midl">
        <div class="pagination_mid">

        <ul> 
            <li v-if='this.$route.params.page >= 4' >
        <router-link :to= "{name: 'page', params: {page: 1}}"> 
                <a href="#" class="wrap_a">1</a>
    </router-link>
            </li>
            
 
            <li class="dots" v-if='this.$route.params.page >= 4'>
                <router-link :to= "{name: 'page', params: {page: 1}}"> 
                <a class="transition" v-if='this.$route.params.page != 1'>...</a>
                   </router-link>
            </li> 

            <li v-if="this.$route.params.page >= 4" ></li>
            <li class = "pagin_mid" v-for="(page, index) in pages" >
                <router-link :to= "{name: 'page', params: {page: page}}"> 
                <a class="wrap_a" :class="{pot: col == page}" >
                    <a  >{{page}}</a>
                </a>
                </router-link>
            </li>
            
            <li v-if="this.$route.params.page < (this.total - 3)" >
                <a style='display: none'>   
                </a>
            </li>  
            
            <li  class="dots" v-if="this.$route.params.page < (this.total - 3)" >
                  <router-link :to= "{name: 'page', params: {page: this.total}}"> 
                <a  class="transition " >...</a>
                </router-link>
            </li> 
            
            

            <li v-if="this.$route.params.page < (this.total - 3)">
            <router-link :to= "{name: 'page', params: {page: this.total}}"> 
            <a  >
                <a class='wrap_a'>{{total}}</a>
            </a>
                </router-link>
            </li>
        </ul>
        </div>
    </div>
        
        <div class="right">
         <div class="buffer_right"></div>
    <div class="pagination_right pag" v-if="this.$route.params.page < this.total">
       <router-link :to= "{name: 'page', params: {page: parseInt(this.$route.params.page) + parseInt(1)}}"> 
     
            <i class="material-icons">navigate_next</i>

            </router-link>
    </div>

        <div v-else>
            <div class="transition_nav"></div>
        </div>
        
        </div>
    </div>
</div>
  
</template>


<script>
import mainPage from './mainPage.vue'
export default {
    name: "paginationtest",
    data: function() {
        return {
            el: 1,
            current: 1,
         
        }
    },
    props: {
        
        col: {
            type: Number,
            default: 1
        },
        dot: {
            type: Number,
            default: 1
        }
        
    },
    
    components: {
        mainPage
    
        
    },
    
    

    computed: {
        rangeEnd: function() {
            var end = this.col + 2
            return (end < this.dot) ? end : this.dot
        },
    
    pages() {
            var pages = []
             for(var i = this.rangeStart; i <= this.rangeEnd; i++) {
                pages.push(i)
            }
            return pages
         },
    
    rangeStart() {
             var start = this.col - 2
             return (start > 0 ) ? start : 1 
         },


    
        total() {
            return this.$store.state.totalPage
        },
        perPage() {
            return this.$store.state.perPage
        },
    

         

    },


    methods: {
        changePage(a){
            var massive = [];
            massive.splice(0,100)
            massive.push(a)
            this.current = massive[0]
        },

      lol(){
          this.current 
          
         
       
    },
        
                

      }  
    ,
    
 watch: {
   '$route.params.page'(al){
       this.changePage(al)
        this.el = this.current
       
       
      
       
         }  
 },

    created(){
        this.changePage(1)
        
    }

  
}
</script>




<style scoped>


    

    .pagination {
        width: 50%;
        margin: auto;
        display: flex;
        flex-flow: nowrap;

       
        

    }
    
    .pagination_mid {
        flex-flow: nowrap;
        justify-content: center;
        display: flex;
          
        
        

    }
    
    .pagination_midl {
        min-width: 700px;
        
            
    }
    

    

    .pagination_left {
        float: left;
        padding-left: 10px;
    
    }
    .pagination_right {
        float: right;
        justify-content: flex-end;
        padding-right: 10px;
        

    }
    
    .pag  i {
        text-decoration: none;
        border-radius: 14px;
        padding: 8px 9px;
        background-color: #039BE5;
        display: block;
        text-align: center;
        margin-bottom: 20px;
        color: white;

    }
    


    .transition_nav {
        margin-left: 200px;
    }
    .pag {
        padding-top: 6px;
        padding-bottom: 0px;
        opacity: 0.80;

    }
    .pag:hover {
        opacity: 1;
    }


    .wrap_a>a:hover {
        text-decoration: none; 
    }
    .wrap_a:hover {
        box-shadow: inset rgba(0,0,0,.2) 0 0px 2px 0.5px;
        transition: 0.3s;
        
        
    }

    
    .wrap_a:active {
        box-shadow: inset rgba(0,0,0,.6) 0 0 2px;
         
    } 
    
    .wrap_a {

        border: 1px solid #D3D1D8;
        opacity: 1;
        border-radius: 7px;
        box-shadow: inset rgba(0,0,0,.1) 0 0 2px;
        text-decoration: none !important;
        opacity: 1;
        background-color: #FFFFFF;
    }
    a{
        display: block;
        width: 40px;
        height: 40px; 
        padding-top: 2.5px;
    }
    a:hover {
        text-decoration: none !important;
        
    }
    
    .pot {
        border-color: #02AAFC;
    }
    
    .dots {
        line-height: 50px;
        vertical-align: bottom;
    }
    ul {
        list-style: none;
        padding: 0;
        margin: 0;
        }
    
    .transition{
        border: none;
        box-shadow: none;
        opacity: 1;
        padding: 0px;
        display: inline-block;
    }
    
    .transition:hover{
        border: none;
        box-shadow: none;
        background-color: #fff;
        color: none;
        opacity: 1;
        
    }
    .buffer {
        padding-right: 155px;
    }

    .buffer_left{
        padding-left: 200px;
    }
    
    .left, .right {
        width: 50px;
    }
    
    .dots:hover  a{
        background-color: #F7FCFF;
    }
    
</style>


