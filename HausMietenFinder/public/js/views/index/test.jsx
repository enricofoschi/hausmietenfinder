(function(Current) {
	"use strict";

	Current.Main = React.createClass({
	    render: function () {
	      return (
	      	<div className="container ptop50">
	      		<Link to="default" params={{splat:'Test'}}>Test</Link> - <Link to="default" params={{splat:''}}>Home</Link>	        
				TEST
			</div>
	      );
	     }
    });
})(defineNamespace("Views.index.test"));