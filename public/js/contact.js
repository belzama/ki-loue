

function contactAction(url, action, callback)
{
    fetch(url, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            "Accept": "application/json",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ action: action })
    })
    .then(res => res.json())
    .then(data => {

        if(!data.success) return;

        if(callback){
            callback(data);
        }
    })
    .catch(err => console.error(err));
}