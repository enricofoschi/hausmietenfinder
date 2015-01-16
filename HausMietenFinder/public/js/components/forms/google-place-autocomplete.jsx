(function(Current) {
	"use strict";

	Current.GooglePlacesAutocomplete = React.createClass({

		mixins: [React.addons.LinkedStateMixin],

		getInitialState: function() {
			return {
				type: "houserent"
			};
		},

		componentDidMount: function(data) {

			var currentNode = this.getDOMNode();
			var thisRef = this;

			Helpers.ThirdParties.GoogleMaps.RequireAPI(function onAPILoaded() {

				var autocompleteInput = $(currentNode).find('.txt-location:first')[0];
				var autocomplete = new google.maps.places.Autocomplete(autocompleteInput);
				google.maps.event.addListener(autocomplete, 'place_changed', function() {
			    	var place = autocomplete.getPlace();

				    if (!place.geometry || !place.geometry.location.k) {
				      return;
				    }

				    thisRef.setState({
				    	latitude: place.geometry.location.k,
				    	longitude: place.geometry.location.D,
				    	location: place.formatted_address
				    });
				});
			});
		},

		submit: function(e) {
			Helpers.UI.DOM.StopPropagation(e);

			if(!this.state.latitude) return;

			if(!$.trim(this.state.location)) {
				Helpers.UI.Notifications.Error('Geben Sie eine Position, bitte.');
			} else {
				this.props.submitCallback({
					location: this.state.location,
					latitude: this.state.latitude,
					longitude: this.state.longitude,
					type: this.state.type
				});
			}
		},

		handleTypeChange: function(event) {
			this.setState({type: "this.refs.mietenGroup.getCheckedValue()"});
		},

		render: function() {
			return (				
				<form onSubmit={this.submit}>
					<div className="input-group">
						<input type="text" valueLink={this.linkState('location')} className="txt-location form-control input-lg" placeholder="Geben Sie hier Ihre Anschrift" />
						<span className="input-group-btn">
							<button disabled={!this.state.latitude} onClick={this.submit} className="btn btn-info btn-lg" type="button">Suchen!</button>
						</span>
					</div>	
					<div className="top15 font20">	
						<Components.Forms.RadioGroup.RadioGroup 
							ref="mietenGroup" 
							name="mietenType" 
							value={this.state.type} 
							onChange={this.handleTypeChange}>
							<label className="no-bold">
								<input type="radio" value="houserent" className="cool-radio" /> 
								<span className="vertical-middle">Haus</span>
							</label>
							<label className="no-bold">
								<input type="radio" value="apartmentrent" className="cool-radio" /> 
								<span className="vertical-middle">Wohnung</span>
							</label>
						</Components.Forms.RadioGroup.RadioGroup>
					</div>
					<input type='submit' className='move-out' value='Suchen!'/>
				</form>
			);
		}
	});
})(defineNamespace("Components.Forms.GoogleMaps"));