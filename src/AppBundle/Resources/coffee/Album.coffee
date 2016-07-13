App = App || {}

# Model: Album item class

App.Album = Backbone.Model.extend
  defaults :
    id : null
    name : ''

    # Get url with album's details. It is used to generate album navigation.
    #
    # @return string Url, where album's details are located

    getAlbumDetailsUrl : () ->
      return Routing.generate 'app_frontend_album',
        'id' : this.id

# Model: Albums collection class

App.AlbumsCollection = Backbone.Collection.extend
  model : App.Album

# View: Album item view class

App.AlbumView = Backbone.Marionette.ItemView.extend
  tagName : 'li',
  className : 'albums-list-item',
  template : '#template-albumItemView'

# View: Album collection view class

App.AlbumsListView = Backbone.Marionette.CollectionView.extend
  tagName : 'ul',
  className : 'albums-list',
  childView : App.AlbumView
