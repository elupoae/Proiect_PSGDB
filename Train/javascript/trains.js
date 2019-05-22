let classTables = document.getElementsByClassName('overflow-y');
let classButton = document.getElementsByClassName('button-g-table');


for (let i = 0; i <= classButton.length; i++) {
    classButton[i].onclick = function() {
        if(classTables[i].style.display === "none") {
            classTables[i].style.display = 'block';
            for (let j = 0; j <= classTables.length; j++) {
                if( i !== j){
                    classTables[j].style.display = 'none';
                }
            }
        }
    };
}
