App = App || {}

App.Controller = Marionette.Controller.extend
  initialize : () ->
    this.rootLayout = new App.RootLayout()
    this.rootLayout.render()

    this.router = new Marionette.AppRouter
      controller : this
      appRoutes:
        "" : "routeAlbums"
        "album/:id" : "routeImagesPaginatedFirstPage"
        "album/:id/page/:page" : "routeImagesPaginatedSpecificPage"

    this.initializeSidebar =_.once this.refreshSidebar

    this.initializeLoaders()

  initializeLoaders : () ->
    this.rootLayout.albums.loader = new App.Loader({ parentElement : this.rootLayout.albums.$el })
    this.rootLayout.albumsWithMaxImages.loader = new App.Loader({ parentElement : this.rootLayout.albumsWithMaxImages.$el })
    this.rootLayout.images.loader = new App.Loader({ parentElement : this.rootLayout.images.$el })

    this.on 'before:fetch:albums', () ->
      this.rootLayout.albums.loader.show()

    this.on 'after:fetch:albums', () ->
      this.rootLayout.albums.loader.hide()

    this.on 'before:fetch:albums-with-max-images', () ->
      this.rootLayout.albumsWithMaxImages.loader.show()

    this.on 'after:fetch:albums-with-max-images', () ->
      this.rootLayout.albumsWithMaxImages.loader.hide()

    this.on 'before:fetch:images', () ->
      this.rootLayout.images.loader.show()

    this.on 'after:fetch:images', () ->
      this.rootLayout.images.loader.hide()

  routeAlbums : () ->
    this.initializeSidebar()

  routeImagesPaginatedFirstPage : (id) ->
    this.initializeSidebar()
    this.refreshMain(id, 1)

  routeImagesPaginatedSpecificPage : (id, page) ->
    this.initializeSidebar()
    this.refreshMain(id, page)

  refreshSidebar : () ->
    this.fetchAlbums()
    this.fetchAlbumsWithMaxImages()

  refreshMain : (id, page) ->
    this.fetchPaginatedImages(id, page);

  fetchAlbums : () ->
    this.trigger('before:fetch:albums');

    albums = new App.AlbumsCollection()
    albums.url = Routing.generate 'app_api_get_albums'
    albums.fetch
      success : (response) =>
        this.rootLayout.renderAlbums response

        this.trigger('after:fetch:albums');

  fetchAlbumsWithMaxImages : () ->
    this.trigger('before:fetch:albums-with-max-images');

    albums = new App.AlbumsCollection()

    albums.url = Routing.generate 'app_api_albums_with_max_images',
      'maxImagesCount' : window.app.albumMaxImagesCount

    albums.fetch
      success : (response) =>
        this.rootLayout.renderAlbumsWithMaxImages response

        this.trigger('after:fetch:albums-with-max-images');

  fetchPaginatedImages : (id, page) ->
    this.trigger('before:fetch:images');

    images = new App.ImagesCollection()

    images.url = Routing.generate 'app_api_images_paginated',
      'albumId' : id
      'pageId' : page

    images.fetch
      success : (response) =>
        if not response.models?
          return

        images = new App.ImagesCollection(response.models[0].attributes.images)
        pagination = response.models[0].attributes.pagination

        this.rootLayout.renderImages images
        this.rootLayout.renderPagination pagination

        this.trigger('after:fetch:images');