App = App || {}

App.RootLayout = Backbone.Marionette.LayoutView.extend
  el : "#overall"
  template : "#template-rootLayoutView"

  regions :
    albums : '#albums'
    albumsWithMaxImages : '#albums-with-max-images'
    images : '#images'

  renderAlbums : (albums) ->
    this.albums.show(new App.AlbumsListView(
      collection : albums
    ))

  renderAlbumsWithMaxImages : (albums) ->
    this.albumsWithMaxImages.show(new App.AlbumsListView(
      collection : albums
    ))

  renderImages : (images) ->
    this.images.show(new App.ImagesListView(
      collection : images
    ))

  renderPagination : (pagination) ->
    $(".pagination").html(pagination);
    # do nothing