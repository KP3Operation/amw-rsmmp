/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import InPatientListCardComponent from "./InpatientListCard.vue";

describe("InPatientListCardComponent", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(InPatientListCardComponent);
        expect(wrapper.exists()).toBe(true);
    });
});
