import Timer from "./timer.js"
import HoverButton from "./hoverbutton.js";
import formatNum from "./numberformat.js";

let update_interval = 50;

const resources = {
    energy: {
        count: 0,
        gain: 0,
    },
}

const prod_buildings = [
    {
        count: 0,
        power: 1,
        gain: 0,
        cost: 10,
    },
    {
        count: 0,
        power: 10,
        gain: 0,
        cost: 400,
    },
    {
        count: 0,
        power: 100,
        gain: 0,
        cost: 16_000,
    },
    {
        count: 0,
        power: 1000,
        gain: 0,
        cost: 640_000,
    },
    {
        count: 0,
        power: 10_000,
        gain: 0,
        cost: 25_600_000,
    },
    {
        count: 0,
        power: 100_000,
        gain: 0,
        cost: 1.02e9,
    },
]

const spans =  {
    prod_buildings: [
        {},
        {},
        {},
        {},
        {},
        {},
    ]
}

const buttons = {
    prod_buildings_buy: [],
}

const paragraphs = {};

function findUIElements() {
    const span_id_mappings = { 
        timer: "timer",
        energy_count: "energy-count",
        energy_gain: "energy-gain",
        energy_manual_gain: "energy-manual-gain",
        loopback_cost: "loopback-cost",
        loopback_gain: "loopback-gain",
    }
    for (let span_id_key in span_id_mappings) {
        let span_id = span_id_mappings[span_id_key];
        spans[span_id_key] = document.getElementById(span_id);
    }

    const button_id_mappings = {
        energy_manual: "energy-manual",
        start_loop: "start-loop",
        start_loopback: "start-loopback"
    }
    for (let button_id_key in button_id_mappings) {
        let button_id = button_id_mappings[button_id_key];
        buttons[button_id_key] = document.getElementById(button_id);
    }

    for (let i = 0; i < 6; i++) {
        let prod_building_span_prefix = `prod-b${i}-`;
        let prod_building_span_id_keys = [
            "power",
            "count",
            "gain",
            "cost",
        ]
        for (let span_id of prod_building_span_id_keys) {
            spans.prod_buildings[i][span_id] = document.getElementById(prod_building_span_prefix + span_id);
        }

        let prod_building_button_buy_id = `prod-b${i}-buy`
        buttons.prod_buildings_buy[i] = document.getElementById(prod_building_button_buy_id);
    }

    const paragraph_id_mappings = {
        loopback_gain: "loopback-gain-p",
        objective: "objective-p",
    }
    for (let paragraph_id_key in paragraph_id_mappings) {
        let paragraph_id = paragraph_id_mappings[paragraph_id_key];
        paragraphs[paragraph_id_key] = document.getElementById(paragraph_id);
    }
    
}

const time = {
    totalTime: new Timer(),
    loopTime: new Timer(),
}

function isLoopActive() {
    return !time.loopTime.stopped;
}

function isOutOfTime() {
    return (time.loopTime.getElapsedTime() >= 600);
}

function formatBigTimer(elapsedTime) {
    let timeRemaining = 600 - elapsedTime;
    if (timeRemaining <= 0) {
        return "00:00.0";
    }
    else {
        let minutes = Math.floor(timeRemaining / 60);
        let minutesString = minutes.toLocaleString(
            "en",
            { minimumIntegerDigits: 2 },
        )
        let seconds = timeRemaining - (minutes * 60);
        let secondsString = seconds.toLocaleString(
            "en",
            { 
                minimumIntegerDigits: 2,
                minimumFractionDigits: 1,
                maximumFractionDigits: 1,
            }
        )
        return `${minutesString}:${secondsString}`
    }
}

function hookupButtons() {
    buttons.start_loop.onclick = startLoop;
    buttons.energy_manual = new HoverButton(
        buttons.energy_manual, 
        1, 
        () => { resources.energy.count += 1; /* modify later */ }
    )

    for (let i = 0; i < 6; i++) {
        buttons.prod_buildings_buy[i] = new HoverButton(
            buttons.prod_buildings_buy[i], 
            1, 
            () => {
            resources.energy.count -= prod_buildings[i].cost;
            prod_buildings[i].count += 1;
        })
    }
}

function updateResourceGains() {
    let totalGains = 0;
    for (let building of prod_buildings) {
        building.gain = building.count * building.power;
        totalGains += building.gain;
    }
    resources.energy.gain = totalGains;
    resources.energy.count += (totalGains * update_interval / 1000);
}

function updateUI() {
    spans.energy_count.innerText = formatNum(resources.energy.count);
    spans.energy_gain.innerText = formatNum(resources.energy.gain);
    spans.energy_manual_gain.innerText = formatNum(1); //modify later
    spans.loopback_cost = formatNum(100_000); //modify later
    if (isLoopbackDeviceCharged()) {
        buttons.start_loopback.className = "done";
    }

    spans.timer.innerText = formatBigTimer(time.loopTime.getElapsedTime());
    if (isLoopActive()) {
        spans.timer.className = "active";
    }
    else if (isOutOfTime()) {
        spans.timer.className = "done";
    }

    for (let i = 0; i < 6; i++) {
        let buildingInfo = prod_buildings[i];
        for (let key in buildingInfo) {
            spans.prod_buildings[i][key].innerText = formatNum(buildingInfo[key]);
        }
    }

    let objectiveText;
    if (!isLoopActive() && !isOutOfTime()) {
        objectiveText = "You are on a space station orbiting a black hole, but the orbit has been destabilized, setting the station on a collision course with the black hole! Unless you do something, the entire station is going to be consumed by the black hole in 10 minutes. Fortunately, one of your colleagues has created a loopback device that will send you back in time before you fall in. Your mission is to charge it before you lose everything."
    }
    else if (isLoopActive() && !isLoopbackDeviceCharged()) {
        objectiveText = "Everything will be consumed unless you charge the loopback device."
    }
    else if (isLoopActive() && isLoopbackDeviceCharged()) {
        objectiveText = "You've successfully charged the loopback device! But the device is giving you some kind of strange shard for charging it, and you think that you should get as many as you can before you loopback."
    }
    else if (isOutOfTime() && !isLoopbackDeviceCharged()) {
        objectiveText = "You failed to charge the loopback device, and you along with the entire station has been sucked into the black hole. :("
    }
    else if (isOutOfTime() && isLoopbackDeviceCharged()) {
        objectiveText = "You are on the cusp of the event horizon, and the loopback device is charged. Now you can travel back in time, and prevent all this from happening in the first place..."
    }
    paragraphs.objective.innerText = objectiveText;
}

function updateHoverButtons() {
    let elapsedTime = update_interval / 1000;
    buttons.energy_manual.update(elapsedTime);
    for (let i = 0; i < 6; i++) {
        let buy_button = buttons.prod_buildings_buy[i];
        if (resources.energy.count >= prod_buildings[i].cost) {
            buy_button.enable();
        }
        else {
            buy_button.disable();
        }
        buy_button.update(elapsedTime);
    }

}

let loopback_gain_p_disabled = true;

function isLoopbackDeviceCharged() {
    return !loopback_gain_p_disabled;
}

function updateLoopbackStats() {
    if (resources.energy.count >= 100_000) {
        loopback_gain_p_disabled = false;
    }
    paragraphs.loopback_gain.hidden = loopback_gain_p_disabled;

    if (isLoopbackDeviceCharged()) {
        let time_shards = Math.floor(resources.energy.count / 100_000);
        spans.loopback_gain.innerText = `${time_shards} time shard${time_shards !== 1 ? "s" : ""}`
    }
}

function disableHoverButtons() {
    buttons.energy_manual.disable();
    for (let i = 0; i < 6; i++) {
        buttons.prod_buildings_buy[i].disable();
    }
}

function enableHoverButtons() {
    buttons.energy_manual.enable();
    for (let i = 0; i < 6; i++) {
        buttons.prod_buildings_buy[i].enable();
    }
}

function update() {
    updateUI();
    if (isLoopActive()) {
        updateResourceGains();
        updateHoverButtons();
        updateLoopbackStats();
    }
    if (isOutOfTime()) {
        endLoop();
    }
}

function initialize() {
    findUIElements();
    hookupButtons();
    disableHoverButtons();
    buttons.start_loopback.disabled = true;
}

function startLoop() {
    time.loopTime.start();
    buttons.start_loop.disabled = true;
    enableHoverButtons();
    update();
}

function endLoop() {
    time.loopTime.stop();
    disableHoverButtons();
    if (resources.energy.count >= 100_000) {
        buttons.start_loopback.disabled = false;
    }
}

initialize();
window.setInterval(update, update_interval);