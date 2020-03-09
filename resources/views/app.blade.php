<!DOCTYPE html>
<html>
<head>
    <title>App</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">  
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
</head>
<body>
    <div id="app">
        <navbar @all="all" @favorites="favorites" @popular="popular" @create-photo="show = true" @create-channel="showChannelModal = true" :user="{{ auth()->user() }}"></navbar>

        {{-- Without image --}}
        {{-- <modal v-show="show" @close="close" :title="photo.id ? 'Edit' : 'Create'">
            <form @submit.prevent="photo.id ? update() : add()" id="photo-form">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" class="form-control" v-model="photo.title">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" id="description" class="form-control" v-model="photo.description">
                </div>
                <div class="form-group">
                    <label for="channel_id">Channel</label>
                    <select id="channel_id" class="form-control" v-model="photo.channel_id">
                        <option v-for="channel in channels" :value="channel.id" v-text="channel.title"></option>
                    </select>
                </div>
                <div class="form-check" v-for="tag in tags">
                    <input class="form-check-input" type="checkbox" :value="tag.id" :id="`tag-${tag.id}`" v-model="photo.tag_ids">
                    <label class="form-check-label" :for="`tag-${tag.id}`" v-text="tag.title"></label>
                </div>
            </form>

            <div class="alert alert-danger mt-3" v-if="errors">
                <ul>
                    <li v-for="error in errors" v-text="error[0]"></li>
                </ul>
            </div>

            <button class="btn btn-primary" :class="{ loading }" :disabled="loading" slot="footer" form="photo-form" v-text="photo.id ? 'Update' : 'Add'"></button>
        </modal> --}}

        {{-- With image --}}
        <modal v-show="show" @close="close" :title="photo.id ? 'Edit' : 'Create'">
            <form @submit.prevent="addWithImage" id="photo-form">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" name="description" id="description" class="form-control">
                </div>
                <div class="form-group">
                    <label for="channel_id">Channel</label>
                    <select name="channel_id" id="channel_id" class="form-control">
                        <option v-for="channel in channels" :value="channel.id" v-text="channel.title"></option>
                    </select>
                </div>
                <div class="form-group">
                    <div class="form-check" v-for="tag in tags">
                        <input class="form-check-input" type="checkbox" name="tag_ids[]" :value="tag.id" :id="`tag-${tag.id}`">
                        <label class="form-check-label" :for="`tag-${tag.id}`" v-text="tag.title"></label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" name="image" id="image" class="form-control-file">
                </div>
            </form>

            <div class="alert alert-danger mt-3" v-if="errors">
                <ul>
                    <li v-for="error in errors" v-text="error[0]"></li>
                </ul>
            </div>

            <button class="btn btn-primary" :class="{ loading }" :disabled="loading" slot="footer" form="photo-form">Add</button>
        </modal>

        <modal v-if="commentedPhoto" title="Comments" @close="commentedPhoto = null">
            <ul v-if="commentedPhoto.comments.length" class="list-group mb-3">
                <li class="list-group-item flex" v-for="comment in commentedPhoto.comments">
                    <span v-text="comment.content"></span>
                    <button class="btn btn-danger btn-sm" @click="removeComment(comment)">Remove</button>
                </li>
            </ul>

            <form @submit.prevent="addComment" id="comment-form">
                <textarea class="form-control" rows="5" v-model="comment.content"></textarea>
            </form>

            <button class="btn btn-primary" slot="footer" form="comment-form">Add</button>
        </modal>

        <modal title="Create Channel" v-show="showChannelModal" @close="closeChannelModal">
            <form @submit.prevent="addChannel" id="channel-form">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" class="form-control" v-model="channel.title">
                </div>
                <div class="form-group">
                    <label for="color">Color</label>
                    <input type="color" id="color" class="form-control" v-model="channel.color">
                </div>
            </form>

            <button class="btn btn-primary" form="channel-form" slot="footer">Add</button>
        </modal>

        <div class="container py-4">
            <div class="row">
                <div class="col-md-8">
                    {{-- Without transitions --}}
                    {{-- <div class="row">
                        <photo v-for="photo in photos.data" :key="photo.id" :photo="photo" @remove="remove" @channel="getPhotosByChannel"></photo>
                    </div> --}}

                    {{-- With transitions --}}
                    <transition-group name="photos" tag="div" class="row">
                        <photo v-for="photo in photos.data" :key="photo.id" :photo="photo" :channels="channels" :tags="tags" @edit="edit" @remove="remove" @channel="getPhotosByChannel" @comment="commentedPhoto = photo"></photo>
                    </transition-group>

                    <pagination :photos="photos" @change="change"></pagination>
                </div>
                <div class="col-md-4">
                    <ul class="list-group">
                        <li class="list-group-item flex" v-for="user in users">
                            <span v-text="user.name"></span>
                            <button class="btn btn-sm" :class="user.followed ? 'btn-primary' : 'btn-secondary'" @click="follow(user)">Follow</button>
                        </li>
                    </ul>

                    <div class="card">
                        <div class="card-header">Filter</div>
                        <div class="card-body">
                            <form @submit.prevent="getPhotos">
                                <div class="form-group">
                                    <label for="filter-title">Title</label>
                                    <input type="text" id="filter-title" class="form-control" v-model="params.title">
                                </div>
                                <div class="form-group">
                                    <div class="form-check" v-for="tag in tags">
                                        <input class="form-check-input" type="checkbox" :value="tag.id" v-model="params.tag_ids">
                                        <label class="form-check-label" v-text="tag.title"></label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Filter</button>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header flex">
                            <span>Sort</span>
                            <span v-if="params.sort">@{{ params.sort }} (@{{ params.direction }})</span>
                        </div>
                        <div class="card-body">
                            <sort @sort="sort"></sort>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">Countdowns</div>
                        <div class="card-body">
                            <small class="text-muted">
                                <countdown v-for="countdown in countdowns" :key="countdown.id" :countdown="countdown" class="mb-3"></countdown>

                                <form @submit.prevent="addCountdown">
                                    <div class="form-group">
                                        <label for="until">Until</label>
                                        <input type="date" id="until" class="form-control" v-model="countdown.until">
                                    </div>
                                    <div class="form-group">
                                        <label for="message">Message</label>
                                        <input type="text" id="message" class="form-control" v-model="countdown.message">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </form>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <flash></flash>
    </div>
    <script src="/js/app.js"></script>
</body>
</html>