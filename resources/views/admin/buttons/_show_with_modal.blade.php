<a style="cursor:pointer" data-remodal-target="show{{$id}}">
    <i class="fa fa-eye fa-2x" ></i>
</a>

<div class="remodal" data-remodal-id="show{{$id}}" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
    <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
    <div class="remodal-content">
        <h2 id="modal1Title">{{$shift->name}}</h2>
        <p id="modal1Desc">
        </p>
    </div>
        <button data-remodal-action="cancel" class="remodal-cancel">{{__('Close')}}</button>
</div>
