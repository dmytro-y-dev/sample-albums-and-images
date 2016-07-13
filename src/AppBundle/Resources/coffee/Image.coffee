App = App || {}

# Model: Image item class

App.Image = Backbone.Model.extend
  defaults:
    id : null
    filename : ''
    description : ''

# Model: Images collection class

App.ImagesCollection = Backbone.Collection.extend
  model : App.Image

# View: Image item view class

App.ImageView = Backbone.Marionette.ItemView.extend
  tagName : 'div'
  className : 'grid-item'
  template : '#template-imageItemView'

# View: Image collection view class

App.ImagesListView = Backbone.Marionette.CollectionView.extend
  tagName : 'div'
  className : 'grid'
  childView : App.ImageView

  onDomRefresh : () ->
    # Hide Masonry grid until images are loaded

    $('.grid').hide()

    # Update Masonry grid after images are loaded

    $('.grid').imagesLoaded () ->
      $('.grid').show () ->
        $('.grid').masonry
          itemSelector : '.grid-item'