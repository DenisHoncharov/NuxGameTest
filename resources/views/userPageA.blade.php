<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Page A</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="main">
        <?php
            if(session('user_id')) {
                $user_id = session('user_id');
            } else {
                return redirect()->route('mainPage');
            }
        ?>

        <a href="{{ route('regenerateLink', $user_id) }}">Generate New One Tmp Link</a>
        <br>
        <ul id="userPageALinks">
             @foreach($userTmpLinks as $userLink)
                <li>
                    <span>{{ $userLink->link }}</span>
                    <button
                        data-link="{{ route('deactivateTmpLink', [$user_id, $userLink->id]) }}"
                        class="deactivateTmpLinkButton"
                        style="display: {{ $userTmpLinks->count() > 1 ? 'block' : 'none' }}"
                    >
                        Deactivate
                    </button>
                </li>
             @endforeach
        </ul>
        <br>
        <a href="#" id="Imfeelinglucky">Imfeelinglucky</a>
        <div id="rollResult" style="display: none">
            <br>
            <span id="number"></span>
            <br>
            <span id="winAmount"></span>
            <br>
            <span id="result"></span>
        </div>
        <br>
        <a id="historyButton" href="#">History</a>
        <div id="history" style="display: none"></div>
    </div>
</body>
</html>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        addRunRollEvent();

        addHistoryEvent();

        deactivateTmpLink();
    });

    function addRunRollEvent() {
        const ImfeelingluckyButton = document.getElementById("Imfeelinglucky");

        if (ImfeelingluckyButton) {
            ImfeelingluckyButton.addEventListener("click", function (e) {
                e.preventDefault();
                const url = "{{ route('runRoll', $user_id) }}";

                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        document.getElementById("number").innerText = `Lucky number: ${data.number}`;
                        document.getElementById("winAmount").innerText = `Win amount: ${data.win}`;
                        document.getElementById("result").innerText = `Result: ${data.rollResult}`;
                        document.getElementById("rollResult").style.display = "block";

                        const history = document.getElementById("history");

                        if (history.style.display === "block") {
                            generateHistoryTable();
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
            });
        }
    }

    function addHistoryEvent() {
        const historyButton = document.getElementById("historyButton");

        if (historyButton) {
            historyButton.addEventListener("click", function (e) {
                e.preventDefault();

                generateHistoryTable();
            });
        }
    }

    function generateHistoryTable() {
        const url = "{{ route('userHistory', $user_id) }}";

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const history = document.getElementById("history");

                history.innerHTML = "";

                const table = document.createElement("table");
                const trHead = document.createElement("tr");
                const th1 = document.createElement("th");
                const th2 = document.createElement("th");
                const th3 = document.createElement("th");
                const th4 = document.createElement("th");

                th1.innerText = "Number";
                th2.innerText = "Win";
                th3.innerText = "Result";
                th4.innerText = "Created at";

                trHead.appendChild(th1);
                trHead.appendChild(th2);
                trHead.appendChild(th3);
                trHead.appendChild(th4);

                table.appendChild(trHead);

                data.rollHistory.forEach(item => {
                    const tr = document.createElement("tr");
                    const td1 = document.createElement("td");
                    const td2 = document.createElement("td");
                    const td3 = document.createElement("td");
                    const td4 = document.createElement("td");

                    td1.innerText = item.number;
                    td2.innerText = item.win;
                    td3.innerText = item.roll_result;
                    td4.innerText = item.created_at;

                    tr.appendChild(td1);
                    tr.appendChild(td2);
                    tr.appendChild(td3);
                    tr.appendChild(td4);

                    table.appendChild(tr);
                });

                history.appendChild(table);
                history.style.display = "block";
            })
            .catch(error => {
                console.error(error);
            });
    }

    function deactivateTmpLink() {
        const deactivateTmpLinkButtons = document.getElementsByClassName("deactivateTmpLinkButton");

        for (const deactivateTmpLinkButtonsKey of deactivateTmpLinkButtons) {
            deactivateTmpLinkButtonsKey.addEventListener("click", function (e) {
                e.preventDefault();
                const url = this.dataset.link;

                fetch(url, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                        "Content-Type": "application/json"
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        const linkList = document.getElementById("userPageALinks");

                        linkList.innerHTML = "";

                        data.userTmpLinks.forEach(item => {
                            const li = document.createElement("li");
                            const span = document.createElement("span");
                            const button = document.createElement("button");

                            span.innerText = item.link;
                            button.innerText = "Deactivate";
                            button.setAttribute("data-link", "{{ route('deactivateTmpLink', [$user_id, $userLink->id]) }}");
                            button.classList.add("deactivateTmpLinkButton");

                            li.appendChild(span);
                            li.appendChild(button);

                            if (data.userTmpLinks.length > 1) {
                                button.style.display = "block";
                            } else {
                                button.style.display = "none";
                            }

                            linkList.appendChild(li);
                        });
                    })
                    .catch(error => {
                        console.error(error);
                    });

            });
        }
    }
</script>
