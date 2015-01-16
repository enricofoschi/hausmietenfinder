(function(Current) {
    "use strict";

    var notificationsElement = null;

    Current.Notification = React.createClass({

        getInitialState: function() {
            return {
                type: 'danger',
                message: 'Oops',
                status: '',
                icon: ''
            };
        },

        componentDidMount: function() {

            var thisRef = this;

            notificationsContainer.on('show', function(event, message, type, icon) {
                thisRef.setState({
                    status: 'in',
                    type: type,
                    icon: icon,
                    message: message
                });

                // Timeout to close it
                if(this.closeTimeout) {
                    window.clearTimeout(this.closeTimeout);
                }

                thisRef.closeTimeout = window.setTimeout(thisRef.close, 3000);
            });
        },

        close: function() {
            this.setState({
                status: ''
            });
        },

        render: function () {
          return (
            <div>
                <div className={'flyover alert alert-' + this.state.type + ' ' + this.state.status} onClick={this.close}>
                    <div className="container">
                        <button className="close">&times;</button>
                        <i className={ 'fa-lg right5 font18 fa fa-' + this.state.icon}></i>
                        <span>{this.state.message}</span>
                    </div>
                </div>
            </div>
          );
        }
    });

    Current.Success = function Success(message) {
        Current.Show(message, 'success', 'check-circle');
    };

    Current.Warning = function Warning(message) {
        Current.Show(message, 'warning', 'warning');
    };

    Current.Error = function Error(message) {
        Current.Show(message, 'danger', 'times-circle');
    };

    Current.Show = function Show(message, type, icon) {
        notificationsContainer.trigger('show', [message, type, icon]);
    };

    // Positioning parent container
    var notificationsContainer = $('<div></div>');
    Helpers.UI.DOM.GetBody().append(notificationsContainer);
    
    // Instantiationg notification element
    notificationsElement = React.createElement(Current.Notification, null);
    React.render(notificationsElement, notificationsContainer[0]);

})(defineNamespace("Helpers.UI.Notifications"));