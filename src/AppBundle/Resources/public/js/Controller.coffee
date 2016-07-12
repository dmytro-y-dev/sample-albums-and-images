App = App || {}

App.Controller = Marionette.Controller.extend
  initialize: () ->
    this.rootLayout = new App.RootLayout()
    this.rootLayout.render()

    this.router = new Marionette.AppRouter
      controller : this
      appRoutes:
        "" : "routeAlbums"
        "album/:id" : "routeImagesPaginatedFirstPage"
        "album/:id/page/:page" : "routeImagesPaginatedSpecificPage"

    this.initializeSidebar =_.once this.refreshSidebar

  routeAlbums: () ->
    this.initializeSidebar()

  routeImagesPaginatedFirstPage: (id) ->
    this.initializeSidebar()
    this.refreshMain(id, 1)

  routeImagesPaginatedSpecificPage: (id, page) ->
    this.initializeSidebar()
    this.refreshMain(id, page)

  refreshSidebar : () ->
    this.fetchAlbums()
    this.fetchAlbumsWithMaxImages()

  refreshMain : (id, page) ->
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