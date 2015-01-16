{% extends "templates/base.volt" %}

{% block content %}

<div class="container">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Picture</th>
                <th>Transit Time</th>
                <th>Address precise</th>
                <th>Price</th>
                <th>Living Space</th>
                <th>Private Offer</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            {% for house in houses %}
                <tr class="{{ house.getStatus() == 1 ? 'success' : (house.getStatus() == 2 ? 'danger' : '')}}" data-id="{{ house.getId() }}">
                    <td><a href="{{ house.getUrl() }}" target="_blank"><img width="100" src="{{ house.getPictureUrl() }}" /></a></td>
                    <td>{{ house.getTransitTime() }}</td>
                    <td>{{ house.isAddressPrecise() }}</td>
                    <td>{{ house.getWarmMiete() }}</td>
                    <td>{{ house.getLivingSpace() }}</td>
                    <td>{{ house.isPrivateOffer() }}</td>
                    <td>
                        <a class="btn btn-default btn-block" target="_blank" href="{{ house.getUrl() }}">View</a>
                        <a class="btn btn-default btn-block action update-status" data-status="0">Reset</a>
                        <a class="btn btn-success btn-block action update-status" data-status="1">Shortlist</a>
                        <a class="btn btn-danger btn-block action update-status" data-status="2">Blacklist</a>
                    </td>
                </tr>
            {% endfor %}
        </tbody>
    </table>
</div>
{% endblock %}