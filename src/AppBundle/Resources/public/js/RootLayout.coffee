App = App || {}

App.RootLayout = Backbone.Marionette.LayoutView.extend
  el : "#overall"
  template : "#template-rootLayoutView"

  regions :
    albums : '#albums'
    albumsWithMaxImages : '#albums-with-max-images'
    images : '#images'

  showAlbums : (albums) ->
    this.albums.show(new App.AlbumsListView(albums))

  showAlbumsWithMaxImages : (albums) ->
    this.albumsWithMaxImages.show(new App.AlbumsListView(albums))

  showImages : (images) ->
    this.images.show(new App.ImagesListView(images))