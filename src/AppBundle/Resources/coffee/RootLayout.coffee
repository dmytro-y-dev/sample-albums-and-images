App = App || {}

# LayoutView: Main application layout

App.RootLayout = Backbone.Marionette.LayoutView.extend
  el : "#overall"
  template : "#template-rootLayoutView"

  regions :
    albums : '#albums'
    albumsWithMaxImages : '#albums-with-max-images'
    images : '#images'

  # Render collection view for albums

  renderAlbums : (albums) ->
    this.albums.show(new App.AlbumsListView(
      collection : albums
    ))

  # Render collection view for albums with max images

  renderAlbumsWithMaxImages : (albums) ->
    this.albumsWithMaxImages.show(new App.AlbumsListView(
      collection : albums
    ))

  # Render collection view for images

  renderImages : (images) ->
    this.images.show(new App.ImagesListView(
      collection : images
    ))

  # Render images pagination

  renderPagination : (pagination) ->
    $(".pagination").html pagination