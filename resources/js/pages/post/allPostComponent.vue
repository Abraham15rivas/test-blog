<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12 mt mb">
                <div class="card">
                    <div class="card-header text-center backcolor">
                        <h5>Lista de posts</h5>
                    </div>

                    <div class="card-body">
                        <span v-if="posts != null && posts.length > 0">
                            <paginate name="posts" :list="posts" :per="5">
                                <li v-for="(post, index) in paginated('posts')" :key="post.id">
                                    <p v-html="`<b>Título:</b> ${post.title}`"></p>
                                    <p v-html="`<b>Autor:</b> ${post.author}`"></p>
                                    <p v-html="`<b>Subtítulo:</b> ${post.slug}`"></p>
                                    <p v-html="`<b>Miniatura:</b>`"></p>
                                    <span v-if="post.image">
                                        <img :src="`/storage/${ post.image }`" alt="" width="20%">
                                    </span>
                                    <span>
                                        <p>
                                            <b>Resumen:</b>
                                            {{ post.description | resume }}
                                        </p>
                                    </span>
                                    <button @click="redirect(post.id, index)" class="btn btn-success">Detalle</button>
                                    <br>
                                    <hr>
                                </li>
                            </paginate>

                            <paginate-links 
                                for="posts"
                                :simple="{
                                    prev: 'Back',
                                    next: 'Next'
                                }"
                                :classes="{
                                    'ul': 'simple-links-container',
                                    '.next > a': 'next-link',
                                    '.prev > a': ['prev-link', 'another-class'] // multiple classes
                                }"
                            ></paginate-links>
                        </span>

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
            posts: [],
            paginate: ['posts']
        }
    },
    filters: {
        resume(value) {
            let resume = value.slice(0, 200);
            return resume + '...'
        }
    },
    methods: {
        async visit(id) {
            try {
                const url   = `/post/visit/new`
                let params  = {
                    id
                }
                let resp    = await axios.post(url, params)
            } catch (error) {
                console.log(error)
            }
        },
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
                        post: this.posts[index]
                    }
                })
            }

            this.visit(id)
        },
        async getPost() {
            try {
                const url       = `/all/posts`
                let responses   = await axios.get(url)

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

<style scoped>
.paginate-links {
    cursor: pointer;
}
</style>