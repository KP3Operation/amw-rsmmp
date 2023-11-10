/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import VitalSignCardComponent from "./VitalSignCard.vue";

describe("VitalSignCardComponent", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(VitalSignCardComponent);
        expect(wrapper.exists()).toBe(true);
    });
});
