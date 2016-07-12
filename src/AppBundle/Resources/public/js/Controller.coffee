App = App || {}

App.Controller = Marionette.Controller.extend
  initialize: () ->
    this.rootLayout = new App.RootLayout()
    this.rootLayout.render()

    this.router = new Marionette.AppRouter
      controller : this
      appRoutes:
        "" : "albums"
        "album/:id" : "imagesPaginatedFirstPage"
        "album/:id/page/:page" : "imagesPaginatedSpecificPage"

  albums: () ->
    this.initializeSidebar()

  imagesPaginatedFirstPage: (id) ->
    this.initializeSidebar()
    this.initializeMain(id, 1)

  imagesPaginatedSpecificPage: (id, page) ->
    this.initializeSidebar()
    this.initializeMain(id, page)

  initializeSidebar : () ->
    this.fetchAlbums()
    this.fetchAlbumsWithMaxImages()

  initializeMain : (id, page) ->
    this.fetchPaginatedImages(id, page);

  fetchAlbums : () ->
    albums = new App.AlbumsCollection()
    albums.url = Routing.generate 'app_api_get_albums'
    albums.fetch
      success : (response) =>
        this.rootLayout.renderAlbums response

  fetchAlbumsWithMaxImages : () ->
    albums = new App.AlbumsCollection()

    albums.url = Routing.generate 'app_api_albums_with_max_images',
      'maxImagesCount' : window.app.albumMaxImagesCount

    albums.fetch
      success : (response) =>
        this.rootLayout.renderAlbumsWithMaxImages response

  fetchPaginatedImages : (id, page) ->
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