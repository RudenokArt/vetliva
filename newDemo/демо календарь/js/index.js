
const hoverTable = color => e =>{
    if(e.target.closest('.comparison')){

        let options = (document.querySelectorAll('.comparison_options__item'));
        let objectsOptions = document.querySelectorAll('.comparison_object');
        let indexOptions = -1;
        if(e.target.classList.contains('comparison_options__item')){
            indexOptions = [].indexOf.call(options,e.target);
        }
        else if(e.target.classList.contains('comprasion_object_options__item')) {
            objectsOptions.forEach(object=>{
                objectItems = object.querySelectorAll('.comprasion_object_options__item');
                if([].indexOf.call(objectItems,e.target) != -1){
                    indexOptions = [].indexOf.call(objectItems,e.target)
                }
            });
        }
        options[indexOptions].style.background = color;
        objectsOptions.forEach(object=>{
            object.querySelectorAll('.comprasion_object_options__item')[indexOptions].style.background = color;
        })
    }

}


document.addEventListener('mouseover', hoverTable('#eee'));
document.addEventListener('mouseout', hoverTable('transparent'));


