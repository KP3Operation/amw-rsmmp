/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import CreateAppointmentPage from "./CreateAppointment.vue";

describe("CreateAppointmentPage", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(CreateAppointmentPage);
        expect(wrapper.exists()).toBe(true);
    });
});
