App = App || {}

App.Controller = Marionette.Controller.extend
  albums: () ->

  imagesPaginatedFirstPage: (id) ->

  imagesPaginatedSpecificPage: (id, page) ->

  renderAlbums : () ->
    albums = new App.AlbumsCollection()
    albums.url = 'api/albums'
    albums.fetch
      success : (albums) =>
        window.app.root.showAlbums
          collection : albums

  renderAlbumsWithMaxImages : () ->
    albums = new App.AlbumsCollection()
    albums.url = 'api/albums/filter-max-images/' + window.app.albumMaxImagesCount
    albums.fetch
      success : (albums) =>
        window.app.root.showAlbumsWithMaxImages
          collection : albums

  renderImages : (id, page) ->
    images = new App.ImagesCollection()
    images.url = 'api/albums/' + id + '/page/' + page
    images.fetch
      success : (images) =>
        window.app.root.showImages
          collection : images