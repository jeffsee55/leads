import ReactDOM from 'react-dom';
import React from 'react';
import Waypoint from 'react-waypoint';

class EndlessScroll extends React.Component {
    constructor(props) {
        super(props);

        var ancestor = document.body;

        this.state = {
            debug: {true},
            scrollableAncestor: {ancestor},
            isLoading: false,
            loadMessage: '',
            things: [],
            page: 1
        }

        this.onEnter = this.onEnter.bind(this);
    }

    onEnter(props) {
        var count = this.state.page +=1;
        this.setState({
            loadMessage: 'Loading',
            page: count
        })
        var query = jQuery('#searchform').serialize();
        jQuery.ajax({
            url: `/wp-admin/admin-ajax.php?${query}&action=q4vr_load_more&page=${this.state.page}`,
            dataType: 'json',
            cache: false,
            success: function(results) {
                if(results.data) {
                    var things = this.state.things.concat(results.data);
                    this.setState({
                        things: things,
                        loadMessage: ''
                    });
                } else {
                    this.setState({
                        loadMessage: 'No more available units'
                    });
                }
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    }

    render() {
        const {things, loadMessage} = this.state;

        return (
            <div>
            <div className={'row'}>
            {things.map(function(item, index) {
                return (
                    <div className="col-sm-4" key={index}>
                        <div className="card">
                            <div className="image-container">
                                <a className="image-link" href={item.displayPermalink}>
                                <img className="unit-card-image" src={item.displayImage}/>
                                { item.displayRate ?
                                    <span className="unit-rate"><em>FROM</em><strong> ${item.displayRate}</strong></span>
                                    :
                                    ''
                                }
                                </a>
                            </div>
                            <div className="unit-card-details">
                                <h4>{item.name}</h4>
                                <h5>{item.address.city}</h5>
                                <h5>Bedrooms: {item.displayBedrooms} | Bathrooms: {item.displayBathrooms}</h5>
                            </div>
                            <a href={item.displayPermalink} className={"btn btn-primary btn-block"}>Check Availability</a>
                        </div>
                    </div>
                )
            })}
            </div>
            <div className={'text-center'}><h5>{loadMessage}</h5></div>
            <Waypoint
            className={'waypoint'}
            id={things.length}
            scollableAncestor={this.scrollableAncestor}
            onEnter={this.onEnter}
            onLeave={this.onLeave}
            onPositionChange={this.onPositionChange}
            />
            </div>
        )
    }
}

if(document.getElementById('q4vr_load_more')) {
    ReactDOM.render(
        <EndlessScroll />, document.getElementById('q4vr_load_more')
    );
}
