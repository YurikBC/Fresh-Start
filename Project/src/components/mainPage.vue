<template>
    
    <div class="mainPage">
    
    <div class="section_1">
    <filtration></filtration>
    </div>
    
    <div class="section_2">

<paginationtest v-bind:col='parseInt(this.$route.params.page)' v-bind:dot="parseInt(this.total)"></paginationtest>
   
     <br>
     
    <div class="container">
    <div class="post" v-for="post in posts">
       <router-link :to= "{name: 'post', params: {id: post.id}}" > 
            <div class="top_cont">
    
                <div class="square"><p>{{dateNew}}</p></div> 
                <div class="triangle"></div>
            </div>
            
        <div class="main_cont">
                <div class="post_img_wrap">
                    <img src="http://bellagambaam.weebly.com/uploads/7/2/5/0/72504765/1424977_orig.jpg">
        </div>
        
        <div class="post_cont">
                <div class="post_header">
                <div class="vacancy_name">
                    <router-link :to= "{name: 'post', params: {id: post.id}}" >{{post.name }} 
                    </router-link> </div>
                    
              
                <div class="icon_star">
                     <favorite v-bind:favPost='parseInt(post.id)'></favorite>
                </div>
            </div>

            <div class="discription">
               <p>{{ post.body + "..."}}</p>
            </div>
            
            <div class="post_bottom">
                    <span class="ex first_ex">
                        <i class="material-icons">work</i> {{post.email}}
                    </span>
                    <span class="ex first_ex">
                        <i class="material-icons">room</i>{{post.email}}
                    </span>

                <div class="ex pro"> 
              
                        <span class="link">
                            <i class="material-icons">view_headline</i>Подробнее
                        </span >
              

                    <div class="square_button"></div>
                </div>
            </div>
                
            </div>
        </div>
          </router-link>
          </div>
    </div>
    <br>
  <paginationtest v-bind:col='parseInt(this.$route.params.page)' v-bind:dot="parseInt(this.total)"></paginationtest>
     </div>
           
    </div>
    
   
</template>


<script> 
import paginationtest from './paginationTest.vue'   
import favorite from './favorite.vue'   
import filtration from './filtration.vue'   
 

export default {
        name: "mainPage",
        props: [],
        data() {
            return {
               
            }
        },
        components: {
            paginationtest, favorite, filtration
        },

        beforeRouteUpdate (to, from, next) {
        next(),next(false),
        this.allPosts(this.$route.params.page)
               
        },

    
    
        computed: {
            total() {
                return this.$store.state.totalPage
            },
            posts() {
                return this.$store.state.posts
            },
            currentPage() {
                return this.$store.state.currentPage
            },
            dateNew() {
                return this.$store.state.dateNew
            }
            

            
        },
    methods: {
        allPosts(page, limit){
        this.$store.dispatch('allPosts', page, limit)
        },
        
            
        
        

    },

   created() {
           this.allPosts(this.$route.params.page)
            this.$store.dispatch('date')
    
                   
    }
    }
    

</script>




<style scoped>
    .mainPage {
          display: flex;
          flex-direction: row;
          height: 100%;
        
    }

    .container {
        display: block;
        box-sizing: border-box;
        margin-top: 20px;
        
      
    }
    
    .post {
        background-color: #FAF8FF;
        min-width: 1000px;
        margin-bottom: 20px;
        max-width: 1000px;
        position: relative;
        padding-left: 0px;
        overflow: hidden;
        border-radius: 6px;
        box-shadow: rgba(166, 190, 205, .2) 0px 3px 0px;
        display: flex;
        flex-flow: row nowrap;
        height: 100%;
        position: relative;
        float: left;
       
     
    }
    
    .main_cont {
         box-shadow: rgba(166, 190, 205, .5) 2px 3px 2px;
        display: flex;
        flex-flow: row nowrap;
        height: 100%;
        min-width: 1000px;
        max-width: 1000px;
        
        
    }

    
    .post:hover{
    box-shadow: rgba(166, 190, 205, .4) -1px 3px 0px;
        background-color: #ffffff;
    transform: scale(1.003);
        transition: 0.1s
        }      
    
    
    .post:hover .square{
    background-color: #02aafc;
    width: 17%;
    margin-left: -7px;
        left: 2px;
        transition: 0.5s;
        }  
    

    
        .post:hover .top_cont{
           width: 100%;
        transition: 0.7s;
            border-bottom: 3px solid #02aafc;
        }  
    
    
        .post_img_wrap {
        display: flex;
        
        position: relative;
        margin-right: 20px;
        margin-bottom: 40px;
        min-width: 18%;
        padding-left: 5px;
        padding-top: 10px;
        background-color: #ffffff;
        height: 100%
  
    }

        img {
            box-shadow: rgba(0,0,0,0.4) 5px 5px 4px;
            position: absolute;  
            top: -16px;  
            bottom: 0;  
            left: 0;  
            right: 0;  
            margin: auto;
            min-width: 115px;
            max-width: 115px;
            
 
    }
    .post:hover img {
       min-width: 123px;
        transition: 0.2s;
    }
    
    .discription > p{
        font-weight: 300;
    }
    
    
        .top_cont {
        border-bottom: 3px solid #028cd1;
        width: 100%;
        height: 20px;
        background: #F7FCFF;
            display: flex;
        
    }
    
    
    .post-cont {
        background-color: #A6BECD;
        margin: 5px;
        display: flex;
        flex-direction: column;
        height: 100%;
        padding: 5px;
        padding-left: 0px;
        position: relative;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        -o-text-overflow: ellipsis;
        -ms-text-overflow: ellipsis;
        
    }
    
    
    .post_header {
        margin-top: 15px;
        height: 22px;
        font-weight: bold;
        text-transform: capitalize;
        white-space: nowrap;
        overflow: hidden;
        font-size: 13px;
        text-overflow: ellipsis;
        -o-text-overflow: ellipsis;
        -ms-text-overflow: ellipsis;
        font-size: 20px;
        display: flex;
        flex-flow: row nowrap;
        
        
        
    }    
    
        .vacancy_name {
            width: 95%;
        display: inline-block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        -o-text-overflow: ellipsis;
        -ms-text-overflow: ellipsis;
        font-size: 20px;

    }

    
    .icon_star {
        width: 5%;
       float: right;
        color: #d18e12;
        
        
        
        
    }
    
    .icon_star>i:hover {
        color: #FFDF00;
    }



    .vacancy_name > a:-webkit-any-link {
      
        text-decoration: none;
         color: black;
        font-weight: 300;
        white-space: nowrap;
        padding-left: 5px;
        overflow: hidden;
        text-overflow: ellipsis;
        -o-text-overflow: ellipsis;
        -ms-text-overflow: ellipsis;
         
        
    }
    .post:hover p {
        padding-left: 10px;
        transition: 0.4s;
    }

    


    
    .vacancy_name:hover {
        transition: all .2s ease;
        cursor: pointer;
    }
    
    .discription {
        margin-right: 60px;
        overflow: hidden;
        padding-right: 40px;
        display: flex;

    }
    
    

    
    .ex {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #a4a7a8;  
        height: 35px;
        padding-top: 5px;
        width: 35%;
         
        
    }
    

    
        .ex:hover {
        color: #F57921;
        transform: scale(1.02);
            
            
    }
    .first_ex:hover i {
        color: #F57921;
        transition: 0.2;
    }
    
    i {
        vertical-align: bottom;
        
    }
    
    .post_bottom {
        margin-bottom: 10px;
        display: flex;
        flex-flow: row nowrap;
        height: 100%;

        
    }
    
  
    

    
    .md-layout {
        overflow: hidden;
        text-overflow: ellipsis;


        
    }
    

        .a {
        display: inline-block;
        line-height: 23px;
        
           
    }

    
    .pro {

        margin-right: 10px;
        overflow: hidden;
        text-overflow: ellipsis;
        -o-text-overflow: ellipsis;
        -ms-text-overflow: ellipsis;
  
    }
    
    .ex > i  {
        color: #4677af
    }
    
    
    .link {
        float: right;
        color: white;
        opacity: 0.8;
        vertical-align: middle;
        font-weight: 300;
        margin-bottom: 5px;
        background-color: #039BE5;
        border-radius: 20px;
        padding: 4px 9px;
        
    }
    
    .link:hover i {
        color: white;
    }
    .link > i{
        margin-bottom: -0.5px;
        color: white;
        font-size: 22px;

    }
    
        .pagination {
        margin-bottom: 100px;
        white-space: nowrap;
        display: flex;
    }



    
    .pag_button {
        background-color: antiquewhite;
        width: 120px;
        border-radius: 13px;
        padding: 15px;
        margin: auto;
    }



    
    .square {
    background: #039BE5;
    width: 18%;
    height: 20px;
    }

    .triangle {
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 0 20px 20px 20px;
    border-color: transparent transparent #039BE5;
    margin-left: -20px;

    }
    
    discription > p {
        color: black;
            text-decoration: none !important; 
            border-bottom: none;
        
            
    }    
    
      discription > p:hover {
        text-decoration: none !important;
          border-bottom: none;
        
            
            
    }
    span {
       color: black ;
    }

 
    
    a:-webkit-any-link   {
    color: black;
    cursor: auto;
    text-decoration: none !important; 
    text-decoration-line: none;
    text-decoration-style: initial;
    text-decoration-color: initial;

    
    }    

    
    .link:active {
       padding: 6px 11px;
        transition: 0.06;
    }
    
    .post_cont:active {
        padding: 1.5px;<paginationtest v-bind:col='parseInt(this.$route.params.page)';
        v-bind:dot="parseInt(this.total)";
        transition: 0.05;

        
    }
   
    .square p {
        margin: 0;
        padding-left: 10px;
        color: white;
    }

    .section_1{
        width: 20%;
    }    
    .section_2{
        float: left;
        width: 100%;
    
    }

</style>
