App = App || {}

App.Album = Backbone.Model.extend
  defaults :
    id : null
    name : ''

    getAlbumDetailsUrl : () ->
      return Routing.generate 'app_frontend_album',
        'id' : this.id

App.AlbumsCollection = Backbone.Collection.extend
  model : App.Album

App.AlbumView = Backbone.Marionette.ItemView.extend
  tagName : 'li',
  className : 'albums-list-item',
  template : '#template-albumItemView'

App.AlbumsListView = Backbone.Marionette.CollectionView.extend
  tagName : 'ul',
  className : 'albums-list',
  childView : App.AlbumView
