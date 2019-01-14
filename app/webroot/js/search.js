Vue.component('artist-item', {
    props: ['artist'],
    template: `<li class="media mb-3 border-bottom">
                    <a href="#" v-on:click="seeArtist(artist)"><img :src="photo" class="mr-3 img-thumbnail" height="60" width="60"></a>
                    <div class="media-body">
                        <h6 class="mt-3 font-weight-bold">{{ artist.name }}</h6>
                    </div>
                </li>`,
    computed: {
        photo: function () {
            return this.artist.images.length > 1 ? this.artist.images[1].url : 'img/blank_img.png';
        }
    },
    methods: {
        seeArtist: function (artist) {
            appSearch.resetDetail()
            appSearch.artist = artist

            axios.get(`artist/albums/${artist.id}`)
                .then(function (response) {
                    if (response.data.success) {
                        appSearch.artist_albums = response.data.data.items;
                    } else {
                        throw response.data.error
                    }
                })
                .catch(function (error) {
                    console.log(error)
                })
        }
    }
})

Vue.component('album-item', {
    props: ['album'],
    template: `<li class="media mb-3 border-bottom">
                    <a href="#" v-on:click="seeAlbum(album)"><img :src="photo" class="mr-3 img-thumbnail" height="60" width="60"></a>
                    <div class="media-body">
                        <h6 class="mt-0 mb-1 font-weight-bold">{{ album.name }}</h6>
                        <div class="row">
                            <div class="col-6">
                                {{ artists }}
                            </div>
                            <div class="col-6">
                            </div>
                        </div>
                    </div>
                </li>`,
    computed: {
        photo: function () {
            return this.album.images.length > 1 ? this.album.images[1].url : 'img/blank_img.png';
        },
        artists: function () {
            var names_artists = [];
            for (var i in this.album.artists) {
                var name_artist = this.album.artists[i].name;
                names_artists.push(name_artist);
            }
            return names_artists.join(', ');
        }
    },
    methods: {
        seeAlbum: function (album) {
            appSearch.resetDetail()
            appSearch.album = album

            axios.get(`album/tracks/${album.id}`)
                .then(function (response) {
                    if (response.data.success) {
                        appSearch.album_tracks = response.data.data.items;
                    } else {
                        throw response.data.error
                    }
                })
                .catch(function (error) {
                    console.log(error)
                })
        }
    }
})

Vue.component('track-item', {
    props: ['track'],
    template: `<li class="media mb-3 border-bottom">
                    <img :src="photo" class="mr-3 img-thumbnail" height="60" width="60">
                    <div class="media-body">
                        <span class="mt-0 mb-1 font-weight-bold">
                            <span v-if="track.explicit" class="badge badge-dark">EXPLICIT</span>
                            {{ track.name }}
                        </span>
                        <div class="row">
                            <div class="col-8">
                                <span class="d-block">{{ artists }}</span>
                                <small class="d-block">Álbum: {{ album }}</small>
                            </div>
                            <div class="col-4 text-right">
                                <button v-if="track.preview_url" v-on:click="togglePreview($event, track.preview_url)" class="btn btn-sm btn-dark float-right">
                                    <i class="fas fa-play"></i>
                                    Preview
                                </button>
                                <small v-else>
                                    Preview no disponible
                                </small>
                            </div>
                        </div>
                    </div>
                </li>`,
    computed: {
        photo: function () {
            return this.track.album.images.length > 1 ? this.track.album.images[1].url : 'img/blank_img.png';
        },
        album: function () {
            return this.track.album.name;
        },
        artists: function () {
            var names_artists = [];
            for (var i in this.track.artists) {
                var name_artist = this.track.artists[i].name;
                names_artists.push(name_artist);
            }
            return names_artists.join(', ');
        }
    },
    methods: {
        togglePreview: function (event, preview_url) {
            var audio_player = document.getElementById('audio-player');
            var icon = event.target.children[0];

            if (audio_player.src == preview_url) {
                if (audio_player.paused) {
                    audio_player.play();
                    icon.className = 'fas fa-stop';
                } else {
                    icon.className = 'fas fa-play';
                    audio_player.pause();
                }
            } else {
                appSearch.audioEnded();
                icon.className = 'fas fa-stop';
                audio_player.src = preview_url;
                audio_player.play();
            }
        }
    }
})

Vue.component('album-track-item', {
    props: ['track'],
    template: `<li class="media mb-2 border-bottom">
                    <div class="media-body">
                        <div class="row">
                            <div class="col-8">
                                <span class="mt-0 mb-1 font-weight-bold">
                                    <span v-if="track.explicit" class="badge badge-dark">EXPLICIT</span>
                                    {{ track.name }}
                                </span>
                                <br><small>{{artists}}</small>
                            </div>
                            <div class="col-4 text-right">
                                <small>{{msToTime(track.duration_ms)}}</small>
                                <button v-if="track.preview_url" v-on:click="togglePreview($event, track.preview_url)" class="btn btn-sm btn-dark float-right">
                                    <i class="fas fa-play"></i>
                                    Preview
                                </button>
                                <small v-else class="d-block">
                                    Preview no disponible
                                </small>
                            </div>
                        </div>
                    </div>
                </li>`,
    computed: {
        artists: function () {
            var names_artists = [];
            for (var i in this.track.artists) {
                var name_artist = this.track.artists[i].name;
                names_artists.push(name_artist);
            }
            return names_artists.join(', ');
        }
    },
    methods: {
        msToTime: function (s) {
            function pad(n, z) {
                z = z || 2;
                return ('00' + n).slice(-z);
            }

            var ms = s % 1000;
            s = (s - ms) / 1000;
            var secs = s % 60;
            s = (s - secs) / 60;
            var mins = s % 60;
            var hrs = (s - mins) / 60;

            return (hrs > 0 ? pad(hrs) + ':' : '') + pad(mins) + ':' + pad(secs);
        },
        togglePreview: function (event, preview_url) {
            var audio_player = document.getElementById('audio-player');
            var icon = event.target.children[0];

            if (audio_player.src == preview_url) {
                if (audio_player.paused) {
                    audio_player.play();
                    icon.className = 'fas fa-stop';
                } else {
                    icon.className = 'fas fa-play';
                    audio_player.pause();
                }
            } else {
                appSearch.audioEnded();
                icon.className = 'fas fa-stop';
                audio_player.src = preview_url;
                audio_player.play();
            }
        }
    }
})

var appSearch = new Vue({
    el: '#appSearch',
    data: {
        textSearched: '',
        textStatus: '',
        tracks: {},
        albums: {},
        artists: {},
        album: {},
        album_tracks: {},
        artist: {},
        artist_albums: {}
    },
    watch: {
        textSearched: function (newTextSearched, oldTextSearched) {
            appSearch.textStatus = '...'
            this.debouncedGetResults()
        }
    },
    created: function () {
        this.debouncedGetResults = _.debounce(this.getResults, 500)
    },
    methods: {
        resetResults: function () {
            appSearch.tracks = {};
            appSearch.artists = {};
            appSearch.albums = {};
            appSearch.textStatus = '';
        },
        resetDetail: function () {
            appSearch.album = {}
            appSearch.album_tracks = {}
            appSearch.artist = {}
            appSearch.artist_albums = {}
        },
        seeMore: function (type) {
            var text = appSearch.textSearched.trim();
            var offset = 0;

            switch (type) {
                case ('artist'):
                    offset = appSearch.artists.offset;
                    break;
                case ('album'):
                    offset = appSearch.albums.offset;
                    break;
                case ('track'):
                    offset = appSearch.tracks.offset;
                    break;
            }

            offset = offset + 5;

            axios.get('search', {
                params: {
                    textoBuscado: text,
                    tipo: type,
                    offset: offset
                }
            })
                .then(function (response) {
                    if (response.data.success) {
                        switch (type) {
                            case ('artist'):
                                appSearch.artists.items.push(...response.data.data.artists.items);
                                appSearch.artists.offset = response.data.data.artists.offset;
                                appSearch.artists.next = response.data.data.artists.next;
                                break;
                            case ('album'):
                                appSearch.albums.items.push(...response.data.data.albums.items);
                                appSearch.albums.offset = response.data.data.albums.offset;
                                appSearch.albums.next = response.data.data.albums.next;
                                break;
                            case ('track'):
                                appSearch.tracks.items.push(...response.data.data.tracks.items);
                                appSearch.tracks.offset = response.data.data.tracks.offset;
                                appSearch.tracks.next = response.data.data.tracks.next;
                                break;
                        }
                    } else {
                        throw response.data
                    }
                })
                .catch(function (error) {
                    console.log(error);
                })
        },
        getResults: function () {
            var text = appSearch.textSearched.trim();

            if (text === "") {
                appSearch.resetResults()
                appSearch.resetDetail()

                return
            }
            appSearch.textStatus = 'Buscando...'
            axios.get('searchAll', {
                params: {
                    textoBuscado: text
                }
            })
                .then(function (response) {
                    if (response.data.success) {
                        appSearch.tracks = response.data.data.tracks
                        appSearch.artists = response.data.data.artists
                        appSearch.albums = response.data.data.albums

                        appSearch.textStatus = `Mostrando resultados para: ${text}`
                    } else {
                        throw response.data.error
                    }
                })
                .catch(function (error) {
                    appSearch.resetResults()
                    appSearch.textStatus = 'Ocurrió un error al realizar la búsqueda.'
                    console.log(error)
                })
        },
        audioEnded: function () {
            var icons_audio = document.getElementsByClassName('fa-stop');

            for (var i = 0; i < icons_audio.length; i++) {
                icons_audio[i].className = 'fas fa-play';
            }
        }
    }
})