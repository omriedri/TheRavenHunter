import { MainInstance } from "./Main.js";
import { Notifier } from "./Notifier.js";
import { Utilities } from "./Utilities.js"

export class Records {

    static ELEMENTS = {
        select: document.querySelector('records select'),
        records: document.querySelector('records tbody'),
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
                    <tr class="d-flex justify-content-between align-items-center w-100 ${you}" scope="row">
                        <td class="text-start member">
                            <button data-bs-toggle="modal" data-bs-target="#profileModal" player="${record?.player.id}">
                                <img src="${record?.player.image}" alt="${record?.player.name}" class="avatar">
                            </button>
                            <span class="name">${record?.player.name}</span>
                        </td>
                        <td class="text-center hour">${record?.hour}</td>
                        <td class="text-center date">${record?.date}</td>
                        <td class="text-center plat">${record?.platform}</td>
                        <td class="text-center diff">${record?.difficulty}</td>
                        <td class="text-center time">${record?.time}</td>
                        <td class="text-center score">${record?.score}</td>
                    </tr>
                `;
            });
        }
        catch (error) {
            new Notifier('', error.message, 'danger');
        }
    }
}