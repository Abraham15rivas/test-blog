<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12 mt mb">
                <div class="card">
                    <div class="card-header text-center backcolor">
                        <h5>Lista de posts</h5>
                    </div>

                    <div class="card-body">
                        <ul v-if="posts != null && posts.length > 0">
                            <li v-for="(post, index) of posts" :key="index">
                                <p v-html="`<b>Título:</b> ${post.title}`"></p>
                                <p v-html="`<b>Autor:</b> ${post.author}`"></p>
                                <p v-html="`<b>Subtítulo:</b> ${post.slug}`"></p>
                                <p v-html="`<b>Miniatura:</b>`"></p>
                                <img :src="`/storage/${ post.image }`" alt="" width="20%">
                                <button @click="redirect(post.id, index)" class="btn btn-success">Show</button>
                                <br>
                                <hr>
                            </li>
                        </ul>
                        <h5 v-else>
                            <b>No hay posts para mostrar</b>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            posts: []
        }
    },
    methods: {
        redirect(id, index) {
            if(this.user != undefined && this.user == 'guest') {
                let anchor  = document.createElement('a')
                anchor.href = `/post/${id}/?guest=true`
                anchor.click()
            } else {
                this.$router.push({ 
                name: 'AnPost', 
                params: {
                        id,
                        event: this.posts[index]
                    }
                })
            }
        },
        async getPost() {
            try {
                const url       = `/all/posts`
                let responses   = await axios.get(url);
                if(responses.data) {
                    this.posts = responses.data
                }                
            } catch (error) {
                console.log(error)
            }
        }
    },
    mounted() {
        this.getPost()
    }
}
</script>