App = App || {}

# Controller: Main controller class

App.Controller = Marionette.Controller.extend
  initialize : () ->
    # Create main LayoutView

    this.rootLayout = new App.RootLayout()
    this.rootLayout.render()

    # Create initializeAlbumsList function, which must be called only once on route match

    this.initializeAlbumsList =_.once this.refreshAlbumsList

    # Initialize loaders and routers

    this.initializeLoaders()
    this.initializeRouter()

  initializeLoaders : () ->
    # Assign loaders to regions

    this.rootLayout.albums.loader = new App.Loader({ parentElement : this.rootLayout.albums.$el })
    this.rootLayout.albumsWithMaxImages.loader = new App.Loader({ parentElement : this.rootLayout.albumsWithMaxImages.$el })
    this.rootLayout.images.loader = new App.Loader({ parentElement : this.rootLayout.images.$el })

    # Attach event handlers on loading change

    this.on 'before:fetch:albums', () ->
      this.rootLayout.albums.loader.show()

    this.on 'after:fetch:albums', () ->
      this.rootLayout.albums.loader.hide()

    this.on 'before:fetch:albums-with-max-images', () ->
      this.rootLayout.albumsWithMaxImages.loader.show()

    this.on 'after:fetch:albums-with-max-images', () ->
      this.rootLayout.albumsWithMaxImages.loader.hide()

    this.on 'before:fetch:images', () ->
      # If images are already displayed, they must be hidden
      # to prevent ugly animation of loader.

      if this.rootLayout.images.currentView
        this.rootLayout.images.currentView.remove();

      # Then show loader.

      this.rootLayout.images.loader.show()

    this.on 'after:fetch:images', () ->
      this.rootLayout.images.loader.hide()

  initializeRouter : () ->
    this.router = new Marionette.AppRouter
      controller : this
      appRoutes:
        "" : "routeAlbums"
        "album/:id" : "routeImagesPaginatedFirstPage"
        "album/:id/page/:page" : "routeImagesPaginatedSpecificPage"

  routeAlbums : () ->
    this.initializeAlbumsList()

  routeImagesPaginatedFirstPage : (id) ->
    this.initializeAlbumsList()
    this.refreshMain(id, 1)

  routeImagesPaginatedSpecificPage : (id, page) ->
    this.initializeAlbumsList()
    this.refreshMain(id, page)

  # Refresh all #albums-content related data and views

  refreshAlbumsList : () ->
    this.fetchAlbums()
    this.fetchAlbumsWithMaxImages()

  # Refresh all #images-content related data and views

  refreshMain : (id, page) ->
    this.fetchPaginatedImages(id, page);

  # Fetch albums data and update #albums region

  fetchAlbums : () ->
    this.trigger('before:fetch:albums');

    albums = new App.AlbumsCollection()

    albums.url = Routing.generate 'app_api_get_albums'

    albums.fetch
      success : (response) =>
        this.rootLayout.renderAlbums response

        this.trigger('after:fetch:albums');

  # Fetch albums with max images data and update #albums-with-max-images region

  fetchAlbumsWithMaxImages : () ->
    this.trigger('before:fetch:albums-with-max-images');

    albums = new App.AlbumsCollection()

    albums.url = Routing.generate 'app_api_albums_with_max_images',
      'maxImagesCount' : window.app.albumMaxImagesCount

    albums.fetch
      success : (response) =>
        this.rootLayout.renderAlbumsWithMaxImages response

        this.trigger('after:fetch:albums-with-max-images');

  # Fetch images data and update #images region

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