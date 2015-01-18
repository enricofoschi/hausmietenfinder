(function(Current) {
  "use strict";

  var pageExtra = 3; // number of page links to show before and after the current

  Current.Pager = React.createClass({
    getInitialState: function() {
      return {};
    },

    onClick: function(e) {
      this.props.paging.onPageChange(parseInt($(e.target).attr("data-page")));
    },

    renderContent: function renderContent(currentPage, index, text) {

      return (
        <li key={index} className={currentPage == this.props.paging.current_page ? 'disabled' : ''}>
          <Link 
            data-page={currentPage}
            to={ this.props.paging.route.name } 
            params={ $.extend({ page: currentPage }, this.props.paging.route.params)}
            onClick={this.onClick} 
            dangerouslySetInnerHTML={{__html: text || currentPage}}
          ></Link>
        </li>
      );
    },

    render: function() {

      var content = ''
      var thisRef = this;

      this.props.paging.current_page = parseInt(this.props.paging.current_page);

      if(this.props.paging.total_elements > 1) {

        /* Setting up page variables */
        var totalPages = Math.ceil(this.props.paging.total_elements / this.props.paging.elements_per_page);

        var minPage = this.props.paging.current_page - pageExtra;
        if(minPage < 1) minPage = 1;

        var maxPage = this.props.paging.current_page + pageExtra;
        if(maxPage > totalPages) maxPage = totalPages;
        
        /* Creating page object */
        var pagingContent = [];
        for(var i = minPage; i <= maxPage; i++) {
          pagingContent.push(i);
        } 

        /* Previous and next */
        var previous = '';
        if(this.props.paging.current_page > 1) {
          previous = this.renderContent(this.props.paging.current_page - 1, Math.random(), '&laquo;');
        }

        var next = '';
        if(this.props.paging.current_page < totalPages) {
          next = this.renderContent(this.props.paging.current_page + 1, Math.random(), '&raquo;');
        }

        /* Rendering */
        content = (
          <nav>
            <ul className="pagination top0 bottom0">
              {previous}
              {
                $.map(pagingContent, function onLoop(k, index) {
                  return thisRef.renderContent(k, index);
                })
              }
              {next}
            </ul>
          </nav>
        );
      }

      return content;
    }
  });
})(defineNamespace("Components.UI.Paging"));