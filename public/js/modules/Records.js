import { MainInstance } from "./Main.js";
import { Notifier } from "./Notifier.js";
import { Utilities } from "./Utilities.js"

export class Records {

    static ELEMENTS = {
        select: document.querySelector('records select'),
        records: document.querySelector('records #recordsList'),
        loader: document.querySelector('records #recordsLoading')
    }

    /**
     * Get records from the server
     * @returns {Promise<Array>}
     */
    static async #getRecords() {
        const selected = this.ELEMENTS.select?.value || 0;
        const response = await Utilities.sendFetch(`api/scores/${selected}`);
        if (!response?.success) {
            new Notifier('', response?.message, 'danger');
            return [];
        }
        return response?.data;
    }

    /**
     * Print records to the table
     * @param {Array} records
     */
    static async print(records = []) {
        this.ELEMENTS.records.innerHTML = '';
        this.ELEMENTS.loader.classList.remove('d-none');
        records = records?.length ? records : await this.#getRecords();
        this.ELEMENTS.loader.classList.add('d-none');
        try {
            records.forEach((record, i) => {
                const you = record?.player.id == MainInstance.LoggedUser?.id ? 'you' : '';
                this.ELEMENTS.records.innerHTML += `
                    <div class="d-flex justify-content-between align-items-center w-100 ${you}">
                        <div class="col-5 col-md-3 text-start member">
                            <button class="btn p-0" data-bs-toggle="modal" data-bs-target="#profileModal" player="${record?.player.id}">
                                <img src="${record?.player.image}" alt="${record?.player.name}" class="avatar" with="40" height="40">
                            </button>
                            <span class="name">${record?.player.name}</span>
                        </div>
                        <div class="col-3 text-center hour d-none d-md-flex">${record?.date} - ${record?.hour}</div>
                        <div class="col-2 col-sm-1 text-center time">${record?.time}</div>
                        <div class="col   text-center score">${record?.score}</div>
                        <div class="col-2 col-md-1 d-flex justify-content-end align-items-center">
                            <div class="col text-center plat mx-1">
                                <img src="${record?.platform?.icon}" alt="${record?.platform?.name}" width="30">
                            </div>
                            <div class="col text-center diff mx-1">
                                <img src="${record?.difficulty?.icon}" alt="${record?.difficulty?.name}" width="25">
                            </div>
                        </div>
                    </div>
                `;
            });
        }
        catch (error) {
            new Notifier('', error.message, 'danger');
        }
    }
}