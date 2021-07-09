<template>
    <div class="padding container-fluid">
        <div class="row">
            <div class="col m12 s12 mt">
                <h3 class="center-align"> Administrar publicaciones ! </h3>
                <a v-if="!form" class="right-align btn-floating btn-large waves-effect waves-light green" @click="form = !form">
                    <i class="material-icons">add</i>
                </a>
                <a v-else class="right-align btn-floating btn-large waves-effect waves-light red" @click="form = !form; clear()">
                    <i class="material-icons">cancel</i>
                </a>
                <div class="mt mb" v-if="form">
                    <form id="form" @submit.prevent="setPost" enctype="multipart/form-data">
                        <label for="title">Título</label>
                        <input type="text" id="title" v-model="title" class="form-control" required>
                        <label for="author">Autor</label>
                        <input type="text" id="author" v-model="author" class="form-control" required>
                        <label for="slug">Slug</label>
                        <input type="text" id="slug" v-model="slug" class="form-control" required>
                        <label for="description">Descripción</label>
                        <textarea class="browser-default" id="despcrition" cols="30" rows="10" v-model="description"></textarea>
                        <md-field>
                            <label>Miniatura</label>
                            <md-file @change="onFileUpload" accept="image/*" required/>
                        </md-field>
                        <label v-if="image" for="description">Preview</label>
                        <img v-if="image" :src="preview" alt="vista previa" width="10%">
                        <br>
                        <label for="video">url de vídeo</label>
                        <input type="url" id="video" v-model="video" class="form-control" required>
                        <label for="category">Opciones</label>
                        <div>
                            <md-checkbox v-model="comment">Permitir comentarios</md-checkbox>
                            <md-checkbox v-model="published">Publicar</md-checkbox>
                        </div>
                        <button class="btn center-align green" v-if="form && !edit">Enviar</button>
                    </form>
                    <button type="buttton" class="btn center-align green" v-if="edit" @click="putPost">Guardar</button>
                </div>
                <div v-else>
                    <md-table class="mt mb" v-if="posts.length > 0">
                        <md-table-row>
                            <md-table-head md-numeric>ID</md-table-head>
                            <md-table-head>Título</md-table-head>
                            <md-table-head>Autor</md-table-head>
                            <md-table-head>Slug</md-table-head>
                            <md-table-head>Descripción</md-table-head>
                            <md-table-head>Miniatura</md-table-head>
                            <md-table-head>Url vídeo</md-table-head>
                            <md-table-head>Permitir Comentarios</md-table-head>
                            <md-table-head>Publicar</md-table-head>
                            <md-table-head>Acciones</md-table-head>
                        </md-table-row>
                        <md-table-row v-for="(post, index) in posts" :key="index">
                            <md-table-cell md-numeric>{{ index + 1 }}</md-table-cell>
                            <md-table-cell>{{ post.title }}</md-table-cell>
                            <md-table-cell>{{ post.author }}</md-table-cell>
                            <md-table-cell>{{ post.slug }}</md-table-cell>
                            <md-table-cell>{{ post.description.slice(0, 10) }}</md-table-cell>
                            <md-table-cell>
                                <img v-if="post.image" :src="`storage/${ post.image }`" alt="vista previa">
                            </md-table-cell>
                            <md-table-cell>{{ post.video }}</md-table-cell>
                            <md-table-cell>{{ post.comment ? 'Si' : 'No' }}</md-table-cell>
                            <md-table-cell>{{ post.published ? 'Si' : 'No' }}</md-table-cell>
                            <md-table-cell>
                                <button class="btn btn-small blue" @click="editPost(index)">Editar</button>
                                <button class="btn btn-small red" @click="delPost(post.id)">Borrar</button>
                            </md-table-cell>
                        </md-table-row>
                    </md-table>
                    <h4 class="text-center" v-else>no hay información para mostrar</h4>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Swal from "sweetalert2"
export default {
    data() {
        return {
            form: false,
            edit: false,
            posts: [],
            id: null,
            title: null,
            author: null,
            slug: null,
            description: null,
            image: null,
            video: null,
            comment: false,
            published: false,
            lastFile: null
        }
    },
    watch: {
        comment() {
            if(this.comment == null) {
                this.comment = false
            }
        },
        published() {
            if(this.published == null) {
                this.published = false
            }
        }
    },
    methods: {
        onFileUpload(evt) {
            this.image      = evt.target.files[0]
            this.lastFile   = URL.createObjectURL(this.image)
        },
        editPost(index) {
            let data            = this.posts[index]
            this.edit           = true
            this.form           = true
            this.id             = data.id
            this.title          = data.title
            this.author         = data.author
            this.slug           = data.slug
            this.description    = data.description
            this.image          = data.image
            this.video          = data.video
            this.comment        = Boolean(data.comment)
            this.published      = Boolean(data.published)
            this.lastFile       = data.image
        },
        clear() {
            this.form           = false
            this.edit           = false
            this.id             = null
            this.title          = null
            this.author         = null
            this.slug           = null
            this.description    = null
            this.image          = null
            this.video          = null
            this.comment        = false
            this.published      = false
            this.lastFile       = null
        },
        async getPost() {
            try {
                const url       = `/admin/posts`
                let response    = await axios.get(url)

                if (response.data) {
                    this.posts = response.data
                }
            } catch (error) {
                console.log(error)
            }
        },
        async setPost() {
            try {
                const url   = `/admin/posts/store`
                let params  = new FormData(document.getElementById("form"))

                params.append("title", this.title)
                params.append("author", this.author)
                params.append("slug", this.slug)
                params.append("description", this.description)
                params.append("image", this.image)
                params.append("video", this.video)
                params.append("comment", Boolean(this.comment))
                params.append("published", Boolean(this.published))

                let response = await axios.post(url, params)

                if (response.data) {
                    this.getPost()
                    this.clear()
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Post guardado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }                
            } catch (error) {
                console.log(error)
            }
        },
        async putPost() {
            try {
                const url   = `/admin/posts/${this.id}/update`
                let params  = new FormData(document.getElementById("form"))

                params.append("title", this.title)
                params.append("author", this.author)
                params.append("slug", this.slug)
                params.append("description", this.description)
                params.append("image", this.image)
                params.append("video", this.video)
                params.append("comment", Boolean(this.comment))
                params.append("published", Boolean(this.published))

                let response = await axios.post(url, params)

                if (response.data) {
                    this.getPost()
                    this.clear()
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Post actualizado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }                
            } catch (error) {
                console.log(error)
            }
        },
        async delPost(id) {
            try {
                const url       = `/admin/posts/${id}/delete`
                let response    = await axios.delete(url)

                if (response.data) {
                    this.getPost()
                    this.clear()
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Post borrado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    })
                } 
            } catch (error) {
                console.log(error)
            }
        }
    },
    computed: {
        preview() {
            if(this.edit) {
                if(this.lastFile.startsWith('blob')) {
                    return this.lastFile
                } else {
                    return `storage/${ this.lastFile }`
                }
            } else {
                return this.lastFile
            }
        }
    },
    mounted() {
        this.getPost()
    }
}
</script>