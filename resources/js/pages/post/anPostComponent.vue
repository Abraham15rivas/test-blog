<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12 mt mb">
                <div class="card">
                    <div class="card-header text-center backcolor">
                        <h5>Post: ¡ {{ post_selected ? post_selected.title : ''}} !</h5>
                    </div>
                    <div class="card-body">
                        <h5 v-text="`Título: ${ post_selected ? post_selected.title : '' }`"></h5>
                        <p v-text="`Autor: ${ post_selected ? post_selected.author : '' }`"></p>
                        <p v-text="`Subtítulo: ${ post_selected ? post_selected.slug : '' }`"></p>
                        <p v-text="`Fecha de creación: ${ post_selected ? post_selected.created_at.slice(0, 10) : '' }`"></p>
                        <p v-text="`Hora de creación: ${ post_selected ? post_selected.created_at.slice(11, 19) : '' }`"></p>
                        <span id="embedA">
                            <iframe
                                class="mt altura"
                                width="100%" 
                                height="350px"
                                src="" 
                                frameborder="0"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </span>
                        <p v-text="`Descripción: ${ post_selected ? post_selected.description : '' }`"></p>
                        <span v-if="post_selected != undefined && post_selected.comment">
                            <hr>
                            <p>Comentarios:</p>
                            <div v-if="comments.length > 0">
                                <ul>
                                    <li v-for="(comment_approved, index) of comments" :key="index">
                                        <p v-text="`Autor: ${JSON.parse(comment_approved.participant_data).email}`"></p>
                                        <p v-text="`Fecha: ${comment_approved.created_at.slice(0, 10)}`"></p>
                                        <p v-text="`Hora: ${comment_approved.created_at.slice(11, 19)}`"></p>
                                        <p v-text="`Comentario: ${comment_approved.content}`"></p>
                                        <span v-if="comment_approved.answer != null">
                                            <div class="text-right">
                                                <h6>Respuesta Admin:</h6>
                                                <blockquote v-text="comment_approved.answer.content"></blockquote>
                                            </div>
                                        </span>
                                        <hr>
                                    </li>
                                </ul>
                            </div>
                            <div v-else class="text-center">
                                <span>
                                    <b>No hay comentarios para mostrar</b>
                                </span>
                            </div>
                            <hr>
                            <form id="form" @submit.prevent="validateComment">
                                <label for="comment">Tu comentario:</label>
                                <textarea class="browser-default" id="comment" cols="30" rows="10" v-model="comment"></textarea>
                                <div class="text-right mt">
                                    <button type="submit" class="btn green">Enviar</button>
                                </div>
                            </form>
                        </span>
                        <hr>
                            <div class="text-right" v-if="this.$store.state.posts.length > 1">
                                <button class="btn primary" @click="prev">Anterior</button>
                                <button class="btn" disabled>
                                    {{ position }}
                                </button>
                                <button class="btn primary" @click="next">siguiente</button>
                                
                            </div>
                            <span v-else>
                                <p class="text-center">No hay mas post publicados</p>
                            </span>
                        <hr>
                        <router-link to="/">
                            <button 
                                class="btn btn center"
                                @click="redirect"
                            >Volver</button>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Swal from "sweetalert2"
import { mapActions, mapGetters } from 'vuex'
export default {
    props: ['post', 'user'],
    data() {
        return {
            url_video: null,
            post_selected: this.post,
            comment: null,
            comments: [],
            email_participant: null,
            id: null
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
        next() {
            let allPost = this.$store.state.posts
            let index = allPost.map(element => element.id).indexOf(this.post_selected.id)
            
            if(index <= allPost.length) {
                let id = allPost[index + 1].id
                this.visit(id)

                this.$router.push({ 
                name: 'AnPost', 
                params: {
                        id,
                        post: allPost[index + 1]
                    }
                })

                setTimeout(() => this.getPost(), 1000)
                setTimeout(() => this.getComments(), 1000)
            }
        },
        prev() {
            let allPost = this.$store.state.posts
            let index = allPost.map(element => element.id).indexOf(this.post_selected.id)

            if(index > 0) {
                let id = allPost[index - 1].id
                this.visit(id)

                this.$router.push({ 
                name: 'AnPost', 
                params: {
                        id: allPost[index - 1].id,
                        post: allPost[index - 1]
                    }
                })

                setTimeout(() => this.getPost(), 1000)
                setTimeout(() => this.getComments(), 1000)
            }
        },
        validateComment() {
            if(this.comment) {
                if(this.post_selected.user_id == this.getIdUser) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'No puedes comentar en un post publicado por ti.!',
                    })
                    this.comment = null
                }

                if(this.user != undefined && this.user == 'guest') {
                    this.setWithEmail()                   
                }

                if(this.getIdUser != null && this.getEmailUser != null) {
                    if(this.post_selected.user_id != this.getIdUser) {
                        this.setComment(this.getEmailUser, this.getIdUser)
                    }
                }
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Oops...',
                    text: `No puede estar vacío!`,
                })
                this.comment = null
            }
        },
        async setWithEmail() {
            const { value: email } = await Swal.fire({
                title: 'Oye, primero considera lo siguiente !',
                html: ` <p>Debe ingresar su correo electrónico!</p>`,
                input: 'email',
                inputLabel: 'Tu correo electrónico',
                inputPlaceholder: 'Ingrese su dirección de correo electrónico'
            })

            if (email) {
                Swal.fire(`Ingrese correo electrónico: ${email}`)
                this.email_participant = email
                this.setComment(email, null)
            }
        },
        async setComment(email, id) {
            try {
                const url   = `/comment/new`
                let params  = {
                    content: this.comment,
                    participant_data: {
                        email,
                        user_id: id
                    },
                    post_id: this.post_selected.id
                }

                let responses = await axios.post(url, params)
                
                if(responses.data) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'info',
                        title: 'Comentario enviado correctamente, está en revisión por parte del Admin una vez aprobado y respondido se mostrará en la lista',
                        showConfirmButton: false,
                        timer: 5000
                    })
                    this.comment = null
                    this.id = null
                    this.getComments()
                }
            } catch (error) {
                console.log(error)
            }
        },
        redirect() {
            if(this.user != undefined && this.user == 'guest') {
                window.location.href = `/`
            }
        },
        setUrlVideo() {
            this.url_video = this.post_selected != undefined ? this.post_selected.video : ''
        },
        urlIframe() {
            const iframe    = document.getElementById('embedA').children[0]
            iframe.src      = this.url_video.replace("watch?v=", "embed/")
        },
        async getComments() {
            try {
                const url       = `/comments/approved/${this.post_selected.id}`
                let responses   = await axios.get(url)
                if(responses.data) {
                    this.comments = responses.data
                }
            } catch (error) {
                console.log(error)
            }
        },
        async getPost() {
            try {
                const url       = `/post/${ this.$route.params.id }`
                let responses   = await axios.get(url)

                if(responses.data) {
                    this.post_selected = responses.data
                }
            } catch (error) {
                console.log(error)
            }
        },
        ...mapActions(['getAllPost'])
    },
    mounted() {
        if(!this.post) {
            this.getPost()
            this.getAllPost()
        }
        setTimeout(() => {
            this.setUrlVideo()
            this.urlIframe()
            this.getComments()
        }, 1000)
        if(this.post_selected != undefined && this.post_selected.comment) {
            this.getComments()
        }
    },
    computed: {
        ...mapGetters(['getRoleUser', 'getIdUser', 'getEmailUser']),
        position() {
            let allPost = this.$store.state.posts
            let index   = allPost.map(element => element.id).indexOf(this.post_selected.id)
            return (index + 1)
        }
    }
}
</script>

<style scoped>
.altura {
    height: 350px !important;
}
</style>